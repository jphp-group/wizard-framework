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
     * @var bool
     */
    public $undecorated = false;

    /**
     * Only for dialogs.
     *
     * @var bool
     */
    public $modal = false;

    /**
     * @var bool
     */
    public $resizable = false;

    /**
     * @var bool
     */
    public $enabled = true;

    /**
     * @readonly
     * @var bool
     */
    public $active;

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

    /**
     *
     */
    public function centerOnScreen()
    {
    }

    /**
     *
     */
    public function toFront()
    {
    }

    /**
     *
     */
    public function toBack()
    {
    }

    public function onActivated(callable $handler)
    {
    }

    public function onDeactivated(callable $handler)
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