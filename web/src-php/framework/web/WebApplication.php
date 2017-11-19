<?php

namespace framework\web;

use framework\core\Annotations;
use framework\core\Application;
use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use php\format\JsonProcessor;
use php\http\HttpResourceHandler;
use php\http\HttpServer;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;
use php\http\WebSocketSession;
use php\io\ResourceStream;
use php\lang\System;
use php\lang\ThreadLocal;
use php\lib\fs;
use php\lib\str;
use php\time\Time;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class WebApplication
 * @package framework\web
 * @method static WebApplication current()
 */
class WebApplication extends Application
{
    /**
     * @var HttpServer
     */
    protected $server;

    /**
     * @var ThreadLocal
     */
    protected $request;

    /**
     * @var ThreadLocal
     */
    protected $response;

    /**
     * @var array
     */
    protected $sessionInstances = [];

    protected function initialize()
    {
        parent::initialize();
        $this->server = new HttpServer();

        $this->request = new ThreadLocal();
        $this->response = new ThreadLocal();

        Logger::addWriter(Logger::stdoutWriter(true));

        Logger::info("Initialize Web Application ({0})", $this->getStamp());

        $this->initializeWebLib();
    }

    /**
     * @param string $class
     * @return Component
     */
    public function getInstance(string $class): Component
    {
        $scope = Annotations::getOfClass('scope', new ReflectionClass($class));

        switch ($scope) {
            case 'request':
                $request = $this->request();
                $instance = $request->attribute('webApp#' . $class);

                if (!$instance) {
                    $instance = new $class();
                    $request->attribute('webApp#' . $class, $instance);
                }

                return $instance;

            case 'session':
                $sessionId = $this->request()->sessionId();

                $instance = $this->sessionInstances[$sessionId][$class];

                if (!$instance) {
                    $instance = new $class();
                    $this->sessionInstances[$sessionId][$class] = $instance;
                }

                return $instance;

            case 'singleton':
                return $this->getSingletonInstance($class);

            default:
                return parent::getInstance($class);
        }
    }

    /**
     * @return HttpServer
     */
    public function server(): HttpServer
    {
        return $this->server;
    }

    /**
     * @return HttpServerRequest
     */
    public function request(): HttpServerRequest
    {
        return $this->request->get();
    }

    /**
     * @return HttpServerResponse
     */
    public function response(): HttpServerResponse
    {
        return $this->response->get();
    }

    public function launch(): void
    {
        parent::launch();

        $host = $this->settings->get('web.server.host', 'localhost');
        $port = $this->settings->get('web.server.port', '5000');

        $this->server->any('/**', function (HttpServerRequest $request, HttpServerResponse $response) {
            $response->status(404);
            $response->body("404. Page not found (/{$request->attribute('**')}).");
        });

        $this->server->listen($host . ":" . $port);

        Logger::info("Web Application run at '{0}:{1}', startup time = {2}ms.", $host, $port, Time::millis() - $this->getInitializeTime());

        $this->server->run();
    }

    protected function initializeWebLib()
    {
        Logger::info("Initialize Web Library (DNext Engine) with stamp ...");

        $jsResource = new ResourceStream('/dnext-engine.js');
        $cssResource = new ResourceStream('/dnext-engine.min.css');
        $mapResource = new ResourceStream('/dnext-engine.js.map');

        $tempDir = System::getProperty('java.io.tmpdir') . "/dnext-engine/";
        fs::makeDir($tempDir);

        fs::copy($jsResource, "$tempDir/engine.js");
        fs::copy($mapResource, "$tempDir/engine.js.map");
        fs::copy($cssResource, "$tempDir/engine.min.css");

        $this->server->get($jsUrl = "/dnext/engine-{$this->getStamp()}.js", new HttpResourceHandler("$tempDir/engine.js"));
        $this->server->get($mapUrl = "/dnext/engine-{$this->getStamp()}.js.map", new HttpResourceHandler("$tempDir/engine.js.map"));
        $this->server->get($cssUrl = "/dnext/engine-{$this->getStamp()}.min.css", new HttpResourceHandler("$tempDir/engine.min.css"));

        Logger::info("Add DNext Engine:");
        Logger::info("\t-> GET {0}", $jsUrl);
        Logger::info("\t-> GET {0}", $cssUrl);
        Logger::info("\t-> WS {0}", "/dnext/ws");

        $this->server->addWebSocket('/dnext/ws', [
            'onConnect' => function (WebSocketSession $session) {
                Logger::info("WS Connect {0} ...", $session->remoteAddress());
                $session->sendText('Hello, Mike!', null);
            },

            'onMessage' => function (WebSocketSession $session, $text) {
                Logger::info("WS message: {0}", $text);
            },

            'onClose' => function (WebSocketSession $session, int $status, string $reason) {
                Logger::info("WS Close.");
            }
        ]);
    }

    public function addUI(string $uiClass)
    {
        $reflectionClass = new ReflectionClass($uiClass);

        $path = Annotations::getOfClass('path', $reflectionClass);

        Logger::info("Add UI ({0})", $uiClass);

        $route = function ($path, callable $handler) use ($uiClass) {
            $this->server->get($path, function (HttpServerRequest $request, HttpServerResponse $response) use ($uiClass, $handler) {
                $this->request->set($request);
                $this->response->set($response);

                /** @var UI $instance */
                $instance = $this->getInstance($uiClass);
                $instance->trigger(new Event('beforeRequest', $instance, $this));

                $handler($instance, $request, $response);

                $instance->trigger(new Event('afterRequest', $instance, $this));
            });

            Logger::info("\t-> GET {0}", $path);
        };

        $route($path, function (UI $ui, HttpServerRequest $request, HttpServerResponse $response) use ($path) {
            $ui->show($request, $response, $path);
        });

        $route("$path/@ui-schema", function (UI $ui, HttpServerRequest $request, HttpServerResponse $response) use ($uiClass) {
            $resource = $ui->getUISchema();

            $response->contentType("application/json");
            $response->body((new JsonProcessor())->format($resource));
        });
    }

    /**
     * @param string $controllerClass
     */
    public function addController(string $controllerClass)
    {
        $class = new ReflectionClass($controllerClass);

        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $baseHttpPath = Annotations::getOfClass('path', $class, '');

        Logger::info("Add controller ({0}):", $controllerClass);

        $events = [];

        foreach ($methods as $method) {
            if ($method->isStatic() || $method->isAbstract()) {
                continue;
            }

            $event = Annotations::getOfMethod('event', $method);

            if ($event) {
                if (is_array($event)) {
                    foreach ($events as $one) {
                        $events[$one] = $method;
                    }
                } else {
                    $events[$event] = $method;
                }
            }
        }

        foreach ($methods as $method) {
            if ($method->isStatic() || $method->isAbstract()) {
                continue;
            }

            $httpPath = '';

            $annotations = Annotations::parseMethod($method);
            $httpMethods = [];

            foreach ($annotations as $name => $value) {
                switch (str::lower($name)) {
                    case 'get':
                    case 'post':
                    case 'put':
                    case 'delete':
                    case 'options':
                    case 'head':
                        $httpMethods[] = $name;
                        break;

                    case 'path':
                        $httpPath = $value;
                        break;
                }
            }

            if (!$httpPath && !$httpMethods) {
                continue;
            }

            $httpPath = "{$baseHttpPath}{$httpPath}";

            Logger::info("\t-> {0} {1} {2}()",
                $httpMethods ? str::join($httpMethods, '|') : '*', $httpPath, $method->getName()
            );

            $this->server->route($httpMethods ?: '*', $httpPath,
                function (HttpServerRequest $request, HttpServerResponse $response) use ($class, $method, $events) {
                    $this->request->set($request);
                    $this->response->set($response);

                    try {
                        /** @var Controller $controller */
                        $controller = $class->newInstance();
                        $controller->initialize($request, $response);

                        foreach ($events as $name => $one) {
                            $controller->on($name, function (Event $e) use ($controller, $one) {
                                $one->invoke($controller, $e);
                            });
                        }

                        $controller->trigger(new Event('beforeRequest', $controller, $this));
                        $result = $method->invoke($controller);
                        $controller->trigger(new Event('afterRequest', $controller, $this));
                        return $result;
                    } catch (\Throwable $e) {
                        if ($controller) {
                            $event = new Event('exception', $controller, $this);
                            $controller->trigger($event);
                        } else {
                            $event = null;
                        }

                        if (!$event || !$event->isConsumed()) {
                            $response->status(505);
                            $errId = str::uuid();

                            $response->body("Oops, there was an error [$errId].");
                            $request->end();

                            Logger::error("{0}, {1}", $e->getMessage(), $errId);
                            Logger::error("\n{0}\n\t-> at {1} on line {2}", $e->getTraceAsString(), $e->getFile(), $e->getLine());
                        }
                    }
                }
            );
        }
    }
}