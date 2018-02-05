<?php
namespace cef;

/**
 * Class CefBrowser
 * @package cef
 */
class CefBrowserWindow
{
    /**
     * @var string
     */
    public $title = '';

    /**
     * NORMAL, UTILITY, POPUP
     * @var string
     */
    public $type = 'NORMAL';

    /**
     * @var array
     */
    public $size = [800, 600];

    /**
     * @var array
     */
    public $position = [0, 0];

    /**
     * CefBrowserWindow constructor.
     * @param CefBrowser $browser
     * @param bool $asDialog
     */
    public function __construct(CefBrowser $browser, bool $asDialog = false)
    {
    }

    /**
     *
     */
    public function show()
    {
    }

    /**
     * Only hide.
     */
    public function hide()
    {
    }

    /**
     * Dispose window.
     */
    public function free()
    {
    }

    public function onClosed(callable $handler)
    {
    }

    public function onClosing(callable $handler)
    {
    }

    public function onOpened(callable $handler)
    {
    }

    public function onIconified(callable $handler)
    {
    }

    public function onDeiconified(callable $handler)
    {
    }

    /**
     * Find window of browser.
     *
     * @param CefBrowser $browser
     * @return CefBrowserWindow
     */
    public static function find(CefBrowser $browser): CefBrowserWindow
    {
    }
}