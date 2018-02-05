<?php
namespace framework\app\desktop;

use cef\CefApp;
use cef\CefBrowser;
use cef\CefBrowserWindow;
use cef\CefClient;
use cef\CefResourceHandler;
use framework\app\desktop\scheme\StreamCefResourceHandler;
use framework\app\desktop\scheme\RouteCefResourceHandler;
use framework\app\desktop\scheme\RouteRequest;
use framework\app\desktop\scheme\RouteResponse;
use framework\core\Application;
use framework\core\Logger;
use php\format\JsonProcessor;
use php\lib\str;

/**
 * Class DesktopApplication
 * @package framework\app\desktop
 */
class DesktopApplication extends Application
{
    /**
     * @var CefApp
     */
    private $cefApp;

    /**
     * @var CefClient
     */
    private $cefClient;

    /**
     * @var CefBrowser
     */
    private $cefBrowser;

    /**
     * @var DesktopBrowserUISession[]
     */
    private $uiSessions;

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var callable
     */
    private $messageRouters = [];

    protected function initialize()
    {
        parent::initialize();

        Logger::addWriter(Logger::stdoutWriter(true));


        CefApp::onStateChanged([$this, 'handleStateChanged']);
        CefApp::registerCustomScheme('wizard', [$this, 'routeRequest'], false);

        $this->cefApp = CefApp::getInstance();
        $this->cefClient = $this->cefApp->createClient();
        $this->cefClient->addMessageRouter([$this, 'handleClientQuery']);
        $this->cefClient->onConsoleMessage([$this, 'handleConsoleMessage']);
        $this->cefClient->onAfterCreated([$this, 'handleAfterCreated']);

        $version = $this->cefApp->getVersion();
        Logger::info("Initialize Desktop Application (chromium = {0}.{1}.{2})",
            $version['CHROME_VERSION_MAJOR'], $version['CHROME_VERSION_MINOR'], $version['CHROME_VERSION_BUILD']
        );
    }

    protected function getUiSession(CefBrowser $browser)
    {
        $key = spl_object_hash($browser);

        if ($session = $this->uiSessions[$key]) {
            return $session;
        }

        Logger::info("Create UI session for browser (key = {0})", $key);
        return $this->uiSessions[$key] = new DesktopBrowserUISession($browser);
    }

    protected function handleStateChanged($state)
    {
    }

    protected function handleAfterCreated(CefBrowser $browser)
    {
        $session = $this->getUiSession($browser);
    }

    protected function handleConsoleMessage(CefBrowser $browser, string $message, string $source, int $line)
    {
        //Logger::warn("{0} in {1} on line {2}", $message, $source ?: 'Unknown', $line);
    }

    protected function handleClientQuery(CefBrowser $browser, string $request, bool $persistence)
    {
        if (str::startsWith($request, 'ws:')) {
            list(, $path, $message) = str::split($request, ':', 3);

            if ($router = $this->messageRouters[$path]) {
                $message = str::parseAs($message, 'json', JsonProcessor::DESERIALIZE_AS_ARRAYS);
                $router($browser, $message);
            }

            return true;
        }

        return false;
    }

    public function routeRequest(CefBrowser $browser, $scheme, array $request): CefResourceHandler
    {
        $url = str::sub($request['uRL'], str::length($scheme) + 2);

        if ($route = $this->routes[$url]) {
            return $route($browser, $request);
        }

        foreach ($this->routes as $path => $route) {
            if (str::endsWith($path, '**')) {
                $path = str::sub($path, 0, str::length($path) - 2);

                if (str::startsWith($url, $path)) {
                    return $route($browser, $request);
                }
            }
        }

        Logger::warn("{0} - 404. Not found.", $url);

        return new RouteCefResourceHandler(function (RouteRequest $request, RouteResponse $response) {
            $response->status = 404;
            $response->contentType = 'text/pain';
            $response->body = '404. Not Found (' . $request->url . ')';
        });
    }

    public function addMessageRouter(string $path, callable $handler)
    {
        $this->messageRouters[$path] = $handler;
    }

    public function addResource(string $path, string $source)
    {
        $this->routes[$path] = function () use ($source) {
            return new StreamCefResourceHandler($source);
        };
    }

    public function addRoute(string $path, callable $handler)
    {
        $this->routes[$path] = function () use ($handler) {
            return new RouteCefResourceHandler($handler);
        };
    }

    /**
     * @return CefClient
     */
    public function getCefClient(): CefClient
    {
        return $this->cefClient;
    }

    public function launch(): void
    {
        parent::launch();
    }
}