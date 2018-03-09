<?php

use cef\CefApp;
use cef\CefBrowser;
use cef\CefBrowserWindow;
use cef\CefCallback;
use cef\CefResourceHandler;
use php\io\Stream;
use php\lib\str;

class WizardSchemeHandler extends CefResourceHandler
{
    private $offset;
    private $data = 'Hello World';

    /**
     * @param array $request
     * @param CefCallback $callback
     * @return bool
     */
    public function processRequest(array $request, CefCallback $callback)
    {
        $this->offset = 0;
        $callback->continue();
        return true;
    }

    /**
     * @param \cef\CefResponse $response
     * @param array $args [length, redirectUrl]
     * @return array|null [length, redirectUrl]
     */
    public function getResponseHeaders(\cef\CefResponse $response, array $args)
    {
        $response->status = 200;
        $response->mimeType = 'text/plain';

        return ['length' => str::length($this->data)];
    }

    /**
     * @param Stream $out
     * @param int $bytesToRead
     * @param CefCallback $callback
     */
    public function readResponse(Stream $out, int $bytesToRead, CefCallback $callback)
    {
        $len = min($bytesToRead, str::length($this->data) - $this->offset);

        if ($this->offset < str::length($this->data)) {
            $out->write(str::sub($this->data, $this->offset, $len));
            $this->offset += $bytesToRead;
        }
    }

    /**
     * @param array $cookie
     * @return bool
     */
    public function canGetCookie(array $cookie)
    {
        return false;
    }

    /**
     * @param array $cookie
     * @return bool
     */
    public function canSetCookie(array $cookie)
    {
        return false;
    }

    /**
     */
    public function cancel()
    {
    }
}

CefApp::registerCustomScheme('wizard', function ($b, $s, $request) {
    print_r($request);
    return new WizardSchemeHandler();
});

$app = CefApp::getInstance();
$client = $app->createClient();

$client->onTitleChange(function (CefBrowser $browser, $title) {
    CefBrowserWindow::find($browser)->title = $title;
});

$browser = $client->createBrowser('wizard://test', false);

$window = new CefBrowserWindow($browser);
$window->title = 'Hello World';
$window->size = [600, 400];
$window->centerOnScreen();
$window->onClosing(function (CefBrowserWindow $window) {
    $window->free();
});
$window->show();