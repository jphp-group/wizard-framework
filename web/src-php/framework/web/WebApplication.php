<?php
namespace framework\web;

use framework\core\Annotations;
use framework\core\Application;
use framework\core\Event;
use framework\core\Logger;
use php\http\HttpServer;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;
use php\lang\ThreadLocal;
use php\lib\str;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class WebApplication
 * @package framework\web
 * @method WebApplication current()
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

    protected function initialize()
    {
        parent::initialize();
        $this->server = new HttpServer();

        $this->request  = new ThreadLocal();
        $this->response = new ThreadLocal();

        Logger::addWriter(Logger::stdoutWriter());
    }

    /**
     * @return HttpServerRequest
     */
    public function request(): HttpServerRequest
    {
    }

    /**
     * @return HttpServerResponse
     */
    public function response(): HttpServerResponse
    {
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

        Logger::info("Web Application run at '{0}:{1}'", $host, $port);

        $this->server->run();
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

                    /** @var Controller $controller */
                    $controller = $class->newInstance();
                    $controller->initialize($request, $response);

                    foreach ($events as $name => $one) {
                        $controller->on($name, function (Event $e) use ($controller, $one) {
                            $one->invoke($controller, $e);
                        });
                    }

                    try {
                        $controller->trigger(new Event('beforeRequest', $controller));
                        $result = $method->invoke($controller);
                        $controller->trigger(new Event('afterRequest', $controller));
                        return $result;
                    } catch (\Throwable $e) {
                        $event = new Event('exception', $controller);
                        $controller->trigger($event);

                        if (!$event->isConsumed()) {
                            throw $e;
                        }
                    }
                }
            );
        }
    }
}