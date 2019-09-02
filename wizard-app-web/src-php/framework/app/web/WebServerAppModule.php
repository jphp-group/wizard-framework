<?php
namespace framework\app\web;

use framework\app\AbstractWebAppModule;
use framework\core\Annotations;
use framework\core\Event;
use framework\core\Logger;
use framework\web\SocketMessage;
use framework\web\UI;
use framework\web\UIModule;
use framework\web\UISocket;
use framework\web\WebApplication;
use framework\web\WebAssets;
use php\format\JsonProcessor;
use php\http\HttpRedirectHandler;
use php\http\HttpResourceHandler;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;
use php\http\WebSocketSession;
use php\io\ResourceStream;
use php\io\Stream;
use php\lang\System;
use php\lib\arr;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;
use php\time\Time;

class WebServerAppModule extends AbstractWebAppModule
{
    /**
     * @var array
     */
    protected $isolatedSessionInstances = [];

    /**
     * @var WebApplication
     */
    protected $app;

    /**
     * @var array
     */
    protected $resources = [
        "engine.min.css" => [
            "type" => "text/css",
            "content" => "res://dnext-engine.min.css"
        ],
        "engine.js" => [
            "type" => "text/javascript",
            "content" => "res://dnext-engine.js"
        ],
    ];

    /**
     * @param WebApplication $app
     */
    public function setApp(WebApplication $app): void
    {
        $this->app = $app;
        $this->app->components[] = $this;
    }

    /**
     * @param UIModule $module
     * @throws \ReflectionException
     */
    public function addModule(UIModule $module) {
        Logger::debug("Register module {0}", (new \ReflectionClass($module))->getName());
        $this->resources = flow($this->resources, $module->getRequiredResources(), $module->getResources())->toMap();
    }

    /**
     * @event addTo
     * @param Event $e
     * @throws \Exception
     */
    protected function handleAddTo(Event $e)
    {
        if ($e->context instanceof WebApplication) {
            $this->app = $e->context;

            $this->app->on('shutdown', function (Event $event) {
                Logger::warn("Shutdown Web UI");

                foreach ($this->isolatedSessionInstances as $sid => $instances) {
                    foreach ($this->uiClasses as $class => $reflection) {
                        /** @var UI $ui */
                        $ui = $instances[$class];

                        if ($ui) {
                            $ui->sendMessage('ui-reload', []);
                        }
                    }
                }

                foreach ($this->isolatedSessionInstances as $sid => $instances) {
                    /** @var UISocket $socket */
                    if ($socket = $instances[UISocket::class]) {
                        $socket->shutdown();
                    }
                }
            }, __CLASS__);

            $this->initializeWebLib($e->context);
        } else {
            throw new \Exception("WebUI module only for Web Applications");
        }
    }

    /**
     * @param string $uiClass
     * @return $this
     * @throws \ReflectionException
     */
    public function addUI(string $uiClass)
    {
        $reflectionClass = $this->uiClasses[$uiClass] = new \ReflectionClass($uiClass);

        $path = Annotations::getOfClass('path', $reflectionClass);

        if ($path === '/') {
            $path = '';
        }

        Logger::info("Add UI ({0})", $uiClass);

        $route = function ($path, callable $handler) use ($uiClass) {
            $this->app->server()->get($path, function (HttpServerRequest $request, HttpServerResponse $response) use ($uiClass, $handler) {
                $this->app->setupRequestAndResponse($request, $response);

                /** @var UI $instance */
                $instance = new $uiClass();
                $instance->trigger(new Event('beforeRequest', $instance, $this));

                $handler($instance, $request, $response);

                $instance->trigger(new Event('afterRequest', $instance, $this));

                UI::setup(null);
            });

            Logger::info("\t-> GET {0}", $path);
        };

        $this->app->server()->addWebSocket("$path/@ws/", [
            'onConnect' => function (WebSocketSession $session) {
            },

            'onMessage' => function (WebSocketSession $session, $text) use ($uiClass) {
                $message = (new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS))->parse($text);
                $type = $message['type'];

                /** @var UISocket $socket */
                $sessionId = $message['sessionId'] . '_' . $message['sessionIdUuid'];

                if (!($socket = $this->isolatedSessionInstances[$sessionId][UISocket::class])) {
                    $this->isolatedSessionInstances[$sessionId][UISocket::class] = $socket = new UISocket();
                }

                /** @var UI $ui */
                if (!($ui = $this->isolatedSessionInstances[$sessionId][$uiClass])) {
                    $this->isolatedSessionInstances[$sessionId][$uiClass] = $ui = new $uiClass($socket);
                }

                $ui->linkSocket($socket);

                Logger::trace("New UI socket message, (type = {0}, sessionId = {1})", $type, $sessionId);

                switch ($type) {
                    case 'initialize':
                        $socket->initialize($uiClass, new WebSocketUISession($session), $message);
                        break;

                    case 'activate':
                        $socket->activate($uiClass, $message);
                        break;

                    default:
                        try {
                            $socket->receiveMessage($uiClass, new SocketMessage($message));
                        } catch (\Throwable $e) {
                            $errId = str::uuid();

                            Logger::error("{0}, {1}", $e->getMessage(), $errId);
                            Logger::error("\n{0}\n\t-> at {1} on line {2}", $e->getTraceAsString(), $e->getFile(), $e->getLine());
                        } finally {
                            UI::setup(null);
                        }

                        break;
                }
            },

            'onClose' => function (WebSocketSession $session) use ($uiClass) {
                /** @var UISocket $socket */
                //$socket = $this->getInstance(UISocket::class);
                //$socket->close($uiClass);
            }
        ]);

        $this->app->server()->get($path, new HttpRedirectHandler("$path/"));

        $route("$path/**", function (UI $ui, HttpServerRequest $request, HttpServerResponse $response) use ($path) {
            $args = [
                'sessionId' => $request->sessionId(),
                'title'     => $request->attribute('~title'),
                'urlArgument' => $request->attribute('**')
            ];

            foreach ($this->resources as $resource => $data) {
                if (!str::contains($args["urlArgument"], $resource)) continue;

                $response->contentType($data["type"]);
                $response->body(Stream::getContents($data["content"]));

                return;
            }

            $body = $ui->makeHtmlView($path, 'window.NX.WebSocketAppDispatcher', $this->resources, $args);
            $response->contentType('text/html');
            $response->body($body);
        });

        return $this;
    }

    protected function initializeWebFontLib(WebApplication $app)
    {
        $dir = fs::abs(System::getProperty('java.io.tmpdir') . "/dnext-engine/material-icons/");
        $tempDir = str::replace($dir, '\\', '/');
        fs::makeDir($tempDir);

        foreach (['material-icons.css',
                     'MaterialIcons-Regular.eot',
                     'MaterialIcons-Regular.ijmap',
                     'MaterialIcons-Regular.svg',
                     'MaterialIcons-Regular.ttf',
                     'MaterialIcons-Regular.woff',
                     'MaterialIcons-Regular.woff2',
                 ] as $file) {
            fs::copy("res://lib/material-icons/$file", "$tempDir/$file");
        }

        $app->components[] = new WebAssets('/dnext/material-icons', $dir);
    }

    protected function initializeWebBootstrapLib(WebApplication $app)
    {
        $dir = fs::abs(System::getProperty('java.io.tmpdir') . "/dnext-engine/bootstrap4/");
        $tempDir = str::replace($dir, '\\', '/');
        fs::makeDir($tempDir);

        foreach (['bootstrap.min.css', 'bootstrap.min.js', 'popper.min.js'] as $file) {
            fs::copy("res://lib/bootstrap4/$file", "$tempDir/$file");
        }

        $app->components[] = new WebAssets('/dnext/bootstrap4', $dir);
    }

    protected function initializeWebJqueryLib(WebApplication $app)
    {
        $dir = fs::abs(System::getProperty('java.io.tmpdir') . "/dnext-engine/jquery/");
        $tempDir = str::replace($dir, '\\', '/');
        fs::makeDir($tempDir);

        foreach (['jquery-3.2.1.min.js'] as $file) {
            fs::copy("res://lib/jquery/$file", "$tempDir/$file");
        }

        $app->components[] = new WebAssets('/dnext/jquery', $dir);
    }

    protected function initializeWebLib(WebApplication $app)
    {
        Logger::info("Initialize Web Library (DNext Engine) with stamp ...");

        $jsResource = new ResourceStream('/dnext-engine.js');
        $cssResource = new ResourceStream('/dnext-engine.min.css');
        $mapResource = new ResourceStream('/dnext-engine.js.map');

        $tempDir = str::replace(fs::abs(System::getProperty('java.io.tmpdir') . "/dnext-engine/"), '\\', '/');
        fs::makeDir($tempDir);

        if ($this->dnextJsFile) {
            $jsFile = $this->dnextJsFile;
            $mapFile = $this->dnextJsFile . ".map";

            if (!fs::isFile($mapFile)) $mapFile = null;
        } else {
            fs::copy($jsResource, $jsFile = "$tempDir/engine.js");
            fs::copy($mapResource, $mapFile = "$tempDir/engine.js.map");
        }

        if ($this->dnextCssFile) {
            $cssFile = $this->dnextCssFile;
        } else {
            fs::copy($cssResource, $cssFile = "$tempDir/engine.min.css");
        }

        $server = $app->server();

        $server->get($jsUrl = "/dnext/engine-{$app->stamp}.js", new HttpResourceHandler($jsFile));
        $server->get($cssUrl = "/dnext/engine-{$app->stamp}.min.css", new HttpResourceHandler($cssFile));

        if ($mapFile) {
            $server->get($mapUrl = "/dnext/engine-{$app->stamp}.js.map", new HttpResourceHandler($mapFile));
        }

        Logger::info("Add DNext Engine:");
        Logger::info("\t-> GET {0} --> {1}", $jsUrl, $jsFile);

        if ($mapUrl) {
            Logger::info("\t-> GET {0} --> {1}", $mapUrl, $mapFile);
        }

        Logger::info("\t-> GET {0} --> {1}", $cssUrl, $cssFile);

        $this->initializeWebFontLib($app);
        $this->initializeWebBootstrapLib($app);
        $this->initializeWebJqueryLib($app);

        if ($this->components->count()) {
            Logger::info("Add DNext Modules:");

            foreach ($this->components as $module) {
                if ($module instanceof UIModule) {
                    $moduleName = str::replace(reflect::typeOf($module), '\\', '/');
                    $prefix = "/dnext/module/" . $moduleName;

                    foreach ($module->getResources() as $resource) {
                        $file = "$tempDir/$moduleName/"  . fs::name($resource);

                        fs::ensureParent($file);
                        $result = fs::copy($resource, $file);

                        if ($result <= 0) {
                            Logger::warn("Failed to add module resource ({0}), not found", $resource);
                            continue;
                        }

                        $server->get(
                            $path = $prefix . "/" . fs::name($resource),
                            new HttpResourceHandler($file)
                        );

                        //Logger::debug("\t-> GET {0} --> {1}", $path, $resource);
                    }

                    foreach ($module->getRequiredResources() as $resource) {
                        $file = "$tempDir/$moduleName/"  . fs::name($resource);

                        fs::ensureParent($file);
                        $result = fs::copy($resource, $file);

                        if ($result <= 0) {
                            Logger::error("Failed to add module resource ({0}), not found", $resource);
                            continue;
                        }

                        $server->get(
                            $path = $prefix . "/" . fs::name($resource),
                            new HttpResourceHandler($file)
                        );

                        Logger::info("\t-> GET {0} --> {1}", $path, $resource);

                        $this->dnextResources[$path] = $path;
                    }
                }
            }
        }
    }

}