<?php
namespace framework\app\desktop;

use cef\CefApp;
use cef\CefBrowser;
use cef\CefBrowserWindow;
use cef\CefClient;
use framework\web\WebApplication;
use php\net\ServerSocket;

class DesktopWebApplication extends WebApplication
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

    protected function initialize()
    {
        parent::initialize();

        $this->cefApp = CefApp::getInstance();
        $this->cefClient = $this->cefApp->createClient();
        $this->cefClient->onTitleChange(function (CefBrowser $browser, string $title) {
            if ($window = CefBrowserWindow::find($browser)) {
                $window->title = $title;
            }
        });

        $this->settings->set('web.server.port', ServerSocket::findAvailableLocalPort());
        $this->settings->set('web.server.host', '127.0.0.1');
    }

    /**
     * @event serverRun
     */
    protected function launchWindow(): void
    {
        $this->cefBrowser = $this->cefClient->createBrowser(
            "http://" . $this->getWebServerHost() . ":" . $this->getWebServerPort(), false
        );

        $cefWindow = new CefBrowserWindow($this->cefBrowser);
        $cefWindow->size = [
            $this->settings->get('app.window.width', 800),
            $this->settings->get('app.window.height', 600),
        ];

        if ($this->settings->get('app.window.centered')) {
            $cefWindow->centerOnScreen();
        }

        $cefWindow->resizable = $this->settings->get('app.window.resizable');

        $cefWindow->title = $this->settings->get('app.window.title');

        $cefWindow->resizable = true;
        $cefWindow->onClosing(function () use ($cefWindow) {
            $cefWindow->free();
            $this->cefApp->dispose();
            $this->shutdown();
        });

        $cefWindow->show();

        if ($this->settings->get('app.window.maximized')) {
            $cefWindow->maximize();
        }
    }


}