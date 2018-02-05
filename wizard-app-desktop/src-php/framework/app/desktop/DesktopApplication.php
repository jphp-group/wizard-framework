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
use php\io\Stream;
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
     * @var array
     */
    private $routes = [];

    protected function initialize()
    {
        parent::initialize();

        Logger::addWriter(Logger::stdoutWriter(true));


        CefApp::onStateChanged([$this, 'handleStateChanged']);
        CefApp::registerCustomScheme('wizard', [$this, 'routeRequest'], false);

        $this->cefApp = CefApp::getInstance();
        $this->cefClient = $this->cefApp->createClient();

        $version = $this->cefApp->getVersion();
        Logger::info("Initialize Desktop Application (chromium = {0}.{1}.{2})",
            $version['CHROME_VERSION_MAJOR'], $version['CHROME_VERSION_MINOR'], $version['CHROME_VERSION_BUILD']
        );
    }

    protected function handleStateChanged($state)
    {
    }

    public function routeRequest(CefBrowser $browser, $scheme, array $request): CefResourceHandler
    {
        $url = str::sub($request['uRL'], str::length($scheme) + 2);

        if ($route = $this->routes[$url]) {
            return $route($browser, $request);
        }

        Logger::warn("{0} - 404. Not found.", $url);

        return new RouteCefResourceHandler(function (RouteRequest $request, RouteResponse $response) {
            $response->status = 404;
            $response->contentType = 'text/pain';
            $response->body = '404. Not Found (' . $request->url . ')';
        });
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

    public function launch(): void
    {
        parent::launch();

        $this->cefBrowser = $browser = $this->cefClient->createBrowser('wizard://app/', false);

        $window = new CefBrowserWindow($browser);
        $window->onClosing(function () use ($window) {
             $window->free();
        });

        $window->size = [1024, 768];
        $window->show();
    }
}