<?php
namespace cef;

/**
 * Class CefBrowser
 * @package cef
 */
class CefBrowser
{
    /**
     * CefBrowser constructor.
     * @see CefClient::createBrowser()
     */
    private function __construct()
    {
    }

    /**
     * @param string $script
     * @param string|null $url
     * @param int $line
     */
    public function executeJavaScript(string $script, string $url = null, int $line = 0)
    {
    }

    /**
     * @return CefBrowser
     */
    public function getDevTools(): CefBrowser
    {
    }
}