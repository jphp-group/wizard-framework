<?php
namespace framework\app\desktop;

use cef\CefApp;
use cef\CefBrowser;
use framework\web\AbstractUISession;
use php\io\IOException;
use php\lib\str;

/**
 * Class DesktopBrowserUISession
 * @package framework\app\desktop
 */
class DesktopBrowserUISession extends AbstractUISession
{
    /**
     * @var CefBrowser
     */
    private $browser;

    /**
     * DesktopBrowserUISession constructor.
     * @param CefBrowser $browser
     */
    public function __construct(CefBrowser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @param string $text
     * @param callable|null $callback
     * @throws IOException
     */
    public function sendText(string $text, ?callable $callback = null): void
    {
        $text = var_export($text);
        $this->browser->executeScript("window.cefMessageHandler('$text');");
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return CefApp::getState() === 'INITIALIZED';
    }
}