<?php
namespace cef;

/**
 * Class CefClient
 * @package cef
 */
class CefClient
{
    /**
     * CefClient constructor.
     * @see CefApp::createClient()
     */
    private function __construct()
    {
    }

    /**
     * @param null|string $url
     * @param bool $isOffscreenRendered
     * @param bool $isTransparent
     * @return CefBrowser
     */
    public function createBrowser(?string $url, bool $isOffscreenRendered, bool $isTransparent = false): CefBrowser
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser, string $request, $persistent): bool
     */
    public function addMessageRouter(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser)
     */
    public function onAfterCreated(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser)
     */
    public function onBeforeClose(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser, string $title)
     */
    public function onTitleChange(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser, string $url)
     */
    public function onAddressChange(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser, string $message, string $source, int $line): bool
     */
    public function onConsoleMessage(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser, string $value)
     */
    public function onStatusMessage(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser, array $request, bool $isRedirect): bool
     */
    public function onBeforeBrowse(callable $invoker)
    {
    }

    /**
     * @param callable $invoker (CefBrowser $browser, array $request): bool
     */
    public function onBeforeResourceLoad(callable $invoker)
    {
    }
}