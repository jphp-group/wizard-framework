<?php
namespace framework\app\web;

use framework\web\AbstractUISession;
use php\http\WebSocketSession;
use php\io\IOException;

/**
 * Class WebSocketUISession
 * @package framework\web
 */
class WebSocketUISession extends AbstractUISession
{
    /**
     * @var WebSocketSession
     */
    protected $session;

    /**
     * WebSocketUISession constructor.
     * @param WebSocketSession $session
     */
    public function __construct(WebSocketSession $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $text
     * @param callable|null $callback
     * @throws IOException
     */
    public function sendText(string $text, ?callable $callback = null): void
    {
        $this->session->sendText($text, $callback);
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->session->isOpen();
    }
}