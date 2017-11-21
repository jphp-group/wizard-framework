<?php
namespace framework\web;

use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use php\format\JsonProcessor;
use php\http\WebSocketSession;

/**
 * Class UIMessageEvent
 * @package framework\web
 */
class UIMessageEvent extends Event
{
    /**
     * @var SocketMessage
     */
    private $message;

    /**
     * @var WebSocketSession
     */
    private $session;

    /**
     * @var string
     */
    private $uiClass;

    public function __construct($type, Component $sender, $context = null,
                                SocketMessage $message, WebSocketSession $session, string $uiClass)
    {
        parent::__construct($type, $sender, $context);
        $this->message = $message;
        $this->session = $session;
        $this->uiClass = $uiClass;
    }

    /**
     * @return SocketMessage
     */
    public function message(): SocketMessage
    {
        return $this->message;
    }

    /**
     * @return WebSocketSession
     */
    public function session(): WebSocketSession
    {
        return $this->session;
    }

    /**
     * @return string
     */
    public function uiClass(): string
    {
        return $this->uiClass;
    }
}

/**
 * Class UISocket
 * @package framework\web
 *
 * @scope session
 */
class UISocket extends Component
{
    /**
     * @var WebSocketSession[]
     */
    protected $sessions;

    /**
     * UISocket constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $uiClass
     * @param WebSocketSession $session
     * @param array $message
     */
    public function initialize(string $uiClass, WebSocketSession $session, array $message)
    {
        $this->sessions[$uiClass] = $session;
    }

    /**
     * @param string $uiClass
     * @param SocketMessage $message
     */
    public function receiveMessage(string $uiClass, SocketMessage $message)
    {
        $session = $this->sessions[$uiClass];

        if ($session) {
            $this->trigger(new UIMessageEvent('message', $this, null, $message, $session, $uiClass));
        }
    }

    /**
     * @param string $uiClass
     * @param callable $handler
     */
    public function onMessage(string $uiClass, callable $handler)
    {
        $this->on('message', function (UIMessageEvent $e) use ($uiClass, $handler) {
            if ($e->uiClass() === $uiClass) {
                $handler($e);
            }
        });
    }

    /**
     * @param string $uiClass
     * @param string $type
     * @param array $data
     * @param callable|null $callback
     */
    public function sendText(string $uiClass, string $type, array $data, callable $callback = null)
    {
        if ($session = $this->sessions[$uiClass]) {
            $data['type'] = $type;
            Logger::trace("Send UI socket message, type = {0}", $type);
            $session->sendText((new JsonProcessor())->format($data), $callback);
        } else {
            Logger::error("Failed to send text, session for {0} UI is not found", $uiClass);
        }
    }

    /**
     * Close socket.
     * @param string $uiClass
     */
    public function close(string $uiClass)
    {
        $this->sessions[$uiClass] = null;
    }
}