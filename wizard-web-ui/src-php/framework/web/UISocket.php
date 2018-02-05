<?php
namespace framework\web;

use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use php\format\JsonProcessor;

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
     * @var AbstractUISession
     */
    private $session;

    /**
     * @var string
     */
    private $uiClass;

    public function __construct($type, Component $sender, $context = null,
                                SocketMessage $message, AbstractUISession $session, string $uiClass)
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
     * @return AbstractUISession
     */
    public function session(): AbstractUISession
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
 */
class UISocket extends Component
{
    /**
     * @var AbstractUISession[][]
     */
    protected $sessions;

    /**
     * @var string[]
     */
    private $activeSessionUuid;

    /**
     * @var bool
     */
    private $excludeActivated = false;

    /**
     * UISocket constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     */
    public function isExcludeActivated(): bool
    {
        return $this->excludeActivated;
    }

    /**
     * @param bool $excludeActivated
     */
    public function setExcludeActivated(bool $excludeActivated)
    {
        $this->excludeActivated = $excludeActivated;
    }

    /**
     * @param string $uiClass
     * @param AbstractUISession $session
     * @param array $message
     */
    public function initialize(string $uiClass, AbstractUISession $session, array $message)
    {
        $this->sessions[$uiClass][$message['sessionIdUuid']] = $session;
        $this->activate($uiClass, $message);
    }

    /**
     * @param string $uiClass
     * @param array $message
     */
    public function activate(string $uiClass, array $message)
    {
        $this->activeSessionUuid[$uiClass] = $message['sessionIdUuid'];
    }

    /**
     * @param string $uiClass
     * @param SocketMessage $message
     */
    public function receiveMessage(string $uiClass, SocketMessage $message)
    {
        $sessions = $this->sessions[$uiClass];

        if ($sessions) {
            $uuid = $message->getData()['sessionIdUuid'];

            if ($session = $sessions[$uuid]) {
                if ($session->isOpen()) {
                    // only once trigger
                    $this->trigger(new UIMessageEvent('message', $this, null, $message, $session, $uiClass));
                } else {
                    unset($this->sessions[$uiClass][$uuid]);
                }
            }
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
        }, __CLASS__);
    }

    /**
     * @param string $uiClass
     * @param string $type
     * @param array $data
     * @param callable|null $callback
     */
    public function sendText(string $uiClass, string $type, array $data, callable $callback = null)
    {
        if ($sessions = $this->sessions[$uiClass]) {
            $data['type'] = $type;

            Logger::trace("Send UI socket message, type = {0}", $type);

            $activatedUuid = $this->activeSessionUuid[$uiClass];

            foreach ($sessions as $uuid => $session) {
                if ($session->isOpen()) {
                    // skip activated
                    if ($this->excludeActivated && $uuid === $activatedUuid) {
                        continue;
                    }

                    $session->sendText((new JsonProcessor())->format($data), $callback);
                } else {
                    unset($this->sessions[$uiClass][$uuid]);
                }
            }
        } else {
            Logger::error("Failed to send text, session for {0} UI is not found", $uiClass);
        }
    }

    /**
     * Shutdown all ws sessions.
     */
    public function shutdown()
    {
        foreach ($this->sessions as $uiClass => $sessions) {
            foreach ($sessions as $uuid => $session) {
                if ($session->isOpen()) {
                    Logger::info("Shutdown WS Session (uuid = {0})", $uuid);
                    $session->close();
                }
            }
        }
    }

}