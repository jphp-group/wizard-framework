<?php
namespace php\gui;

use cef\CefBrowser;

/**
 * Class UXChromium
 * @package php\gui
 */
class UXChromium extends UXNode
{
    /**
     * @var CefBrowser
     */
    public $engine;

    /**
     * UXChromium constructor.
     * @param CefBrowser $engine
     */
    public function __construct(CefBrowser $engine)
    {
        $this->engine = $engine;
    }
}