<?php
namespace framework\web;

use framework\core\Application;
use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use framework\web\ui\UXContainer;
use framework\web\ui\UXNode;
use php\format\JsonProcessor;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;
use php\http\WebSocketSession;
use php\io\Stream;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;

/**
 * Class UI
 * @package framework\web
 *
 * @scope session
 */
abstract class UI extends Component
{
    private $view;

    /**
     * @var UISocket
     */
    private $socket;

    /**
     * UI constructor.
     */
    public function __construct()
    {
        $this->view = $this->makeView();

        $this->view->connectToUI($this);

        $this->socket = Application::current()->getInstance(UISocket::class);
        $this->socket->onMessage(reflect::typeOf($this), function (UIMessageEvent $e) {
            $this->onMessage($e);
        });
    }

    /**
     * @return UXNode
     */
    abstract protected function makeView(): UXNode;

    /**
     * @return UXNode
     */
    public function getView(): UXNode
    {
        return $this->view;
    }

    /**
     * @return array
     */
    final public function getUISchema(): array
    {
        return $this->view->uiSchema();
    }

    /**
     * @param string $uuid
     * @return UXNode|null
     */
    protected function findNodeByUuid(string $uuid, ?UXNode $view): ?UXNode
    {
        if ($view) {
            if ($view->getUuid() === $uuid) {
                return $view;
            }

            if ($view instanceof UXContainer) {
                foreach ($view->children as $child) {
                    if ($found = $this->findNodeByUuid($uuid, $child)) {
                        return $found;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param UIMessageEvent $e
     */
    protected function onMessage(UIMessageEvent $e)
    {
        $message = $e->message();

        switch ($message->getType()) {
            case 'ui-trigger':
                ['uuid' => $uuid, 'event' => $event, 'data' => $data] = $message->getData();
                $node = $this->findNodeByUuid($uuid, $this->view);

                if ($node) {
                    Logger::info("Trigger event, uuid = {0}, event = {1}", $uuid, $event);
                    $node->trigger(new Event($event, $node, $this, $data));
                } else {
                    Logger::warn('Failed to trigger "{0}", node with uid = {1} is not found', $event, $uuid);
                }

                break;

            case 'ui-user-input':
                ['uuid' => $uuid, 'data' => $data] = $message->getData();
                $node = $this->findNodeByUuid($uuid, $this->view);

                if ($node) {
                    $node->provideUserInput((array) $data);
                } else {
                    Logger::warn('Failed to provide user input, node with uid = {1} is not found', $uuid);
                }

                break;

            default:
                Logger::warn('Unknown socket message (type = {0})', $message->getType());
                break;
        }
    }

    /**
     * @param HttpServerRequest $request
     * @param HttpServerResponse $response
     * @param string $path
     */
    final public function show(HttpServerRequest $request, HttpServerResponse $response, string $path)
    {
        $moduleFile = reflect::typeModule(__CLASS__)->getName();
        $ext = fs::ext($moduleFile);
        $moduleFile = str::sub($moduleFile, 0, str::length($moduleFile) - str::length($ext) - 1) . ".html";

        $body = fs::get($moduleFile);

        $body = str::replace($body, '{{dnextCSSUrl}}', '/dnext/engine-' . Application::current()->getStamp() . '.min.css');
        $body = str::replace($body, '{{dnextJSUrl}}', '/dnext/engine-' . Application::current()->getStamp() . '.js');

        $body = str::replace($body, '{{uiSchemaUrl}}', "$path/@ui-schema");
        $body = str::replace($body, '{{uiSocketUrl}}', "$path/@ws/");
        $body = str::replace($body, '{{sessionId}}', $request->sessionId());

        $response->contentType('text/html');
        $response->body($body);
    }

    /**
     * @param string $message
     */
    public function alert(string $message)
    {
        $this->socket->sendText(reflect::typeOf($this), 'ui-alert', ['text' => $message]);
    }

    /**
     * @param UXNode $node
     * @param string $property
     * @param $value
     */
    public function changeNodeProperty(UXNode $node, string $property, $value)
    {
        $this->socket->sendText(reflect::typeOf($this), 'ui-set-property', [
            'uuid' => $node->getUuid(),
            'property' => $property,
            'value' => $value
        ]);
    }

    /**
     * @param UXNode $node
     * @param string $method
     * @param array $args
     */
    public function callNodeMethod(UXNode $node, string $method, array $args = [])
    {
        $this->socket->sendText(reflect::typeOf($this), 'ui-call-method', [
            'uuid' => $node->getUuid(),
            'method' => $method,
            'args' => $args
        ]);
    }

    /**
     * @param UXNode $node
     * @param string $eventType
     */
    public function addEventLink(UXNode $node, string $eventType)
    {
        $this->socket->sendText(reflect::typeOf($this), 'ui-event-link', [
            'uuid' => $node->getUuid(),
            'event' => str::lower($eventType),
        ]);
    }
}