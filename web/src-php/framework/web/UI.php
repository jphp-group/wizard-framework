<?php
namespace framework\web;

use framework\core\Annotations;
use framework\core\Application;
use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use framework\web\ui\UIContainer;
use framework\web\ui\UINode;
use framework\web\ui\UIWindow;
use php\format\JsonProcessor;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;
use php\http\WebSocketSession;
use php\io\Stream;
use php\lang\Thread;
use php\lang\ThreadLocal;
use php\lib\arr;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;

/**
 * Class UI
 * @package framework\web
 *
 * @property string $requestUrl
 * @property string $hash
 */
abstract class UI extends Component
{
    private $view;

    /**
     * @var array
     */
    protected $location = ['path' => '', 'host' => '', 'contextUrl' => '', 'port' => 0, 'protocol' => '', 'target' => ''];

    /**
     * @var UIWindow[]
     */
    private $windows = [];

    /**
     * @var UISocket
     */
    protected $socket;

    /**
     * @var ThreadLocal
     */
    private static $current;

    /**
     * UI constructor.
     * @param UISocket $socket
     */
    public function __construct(?UISocket $socket = null)
    {
        $this->view = $this->makeView();
        $this->view->connectToUI($this);

        if ($this->socket) {
            $this->linkSocket($socket);
        }
    }

    /**
     * @return UINode
     */
    abstract protected function makeView(): UINode;

    /**
     * @return string
     */
    protected function getRoutePath(): string
    {
        $path = Annotations::getOfClass('path', new \ReflectionClass($this), '/');

        if (is_array($path)) {
            return arr::first($path);
        } else {
            return $path;
        }
    }

    /**
     * @return string
     */
    protected function getRequestUrl(): ?string
    {
        return $this->location['path'];
    }

    /**
     * @return string
     */
    protected function getHash(): string
    {
        return $this->location['hash'];
    }

    /**
     * @param string $hash
     */
    protected function setHash(string $hash)
    {
        $this->location['hash'] = $hash;

        $this->sendMessage('page-set-properties', [
            'hash' => $hash
        ]);
    }

    /**
     * @return null|string
     */
    public function getFullRequestUrl(): ?string
    {
        if ($this->getRequestUrl() !== null) {
            return "{$this->getRoutePath()}/{$this->getRequestUrl()}";
        }

        return null;
    }

    /**
     * @param UISocket $socket
     */
    public function linkSocket(UISocket $socket)
    {
        $this->socket = $socket;
        $this->socket->onMessage(reflect::typeOf($this), function (UIMessageEvent $e) {
            $this->onMessage($e);
        });
    }

    /**
     * @param UIWindow $window
     */
    public function addWindow(UIWindow $window)
    {
        $this->windows[$window->uuid] = $window;

        $window->connectToUI($this);
        $this->socket->sendText(reflect::typeOf($this), 'ui-create-node', ['schema' => $window->uiSchema()]);
    }

    /**
     * @param UIWindow $window
     */
    public function removeWindow(UIWindow $window)
    {
        unset($this->windows[$window->uuid]);
        $window->disconnectUI();
    }

    /**
     * @return UINode
     */
    public function getView(): UINode
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
     * @param $value
     * @return array|mixed
     */
    protected function parseValue($value)
    {
        if (is_array($value)) {
            if (sizeof($value) === 1) {
                $key = arr::firstKey($value);
                $v = $value[$key];

                switch ($key) {
                    case '$node':
                        return $this->findNodeByUuidGlobally($v);
                }
            }

            foreach ($value as $key => $one) {
                $value[$key] = $this->parseValue($one);
            }

            return $value;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return array|mixed
     */
    protected function prepareValue($value)
    {
        if (is_array($value)) {
            foreach ($value as &$one) {
                $one = $this->prepareValue($one);
            }

            return $value;
        }

        if ($value instanceof UINode) {
            if ($this->findNodeByUuidGlobally($value->uuid)) {
                return ['$node' => $value->uuid];
            } else {
                return ['$createNode' => $value->uiSchema()];
            }
        }

        if (is_object($value)) {
            return $this->prepareValue((array) $value);
        }

        return $value;
    }

    public function findNodeByUuidGlobally(string $uuid): ?UINode
    {
        $found = $this->findNodeByUuid($uuid, $this->view);

        if (!$found) {
            foreach ($this->windows as $window) {
                $found = $this->findNodeByUuid($uuid, $window);

                if ($found) {
                    break;
                }
            }
        }

        return $found;
    }

    /**
     * @param string $uuid
     * @return UINode|null
     */
    protected function findNodeByUuid(string $uuid, ?UINode $view): ?UINode
    {
        if ($view) {
            if ($view->getUuid() === $uuid) {
                return $view;
            }

            if ($view instanceof UIContainer) {
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
                $node = $this->findNodeByUuidGlobally($uuid);

                if ($node) {
                    $uiSchemaEvents = $node->uiSchemaEvents();

                    $events = [$event];

                    foreach ($uiSchemaEvents as $key => $v) {
                        if ($v === $event) {
                            $events[] = $key;
                        }
                    }

                    foreach ($events as $event) {
                        Logger::info("Trigger event, uuid = {0}, event = {1}", $uuid, $event);
                        $node->trigger(new Event($event, $node, $this, $data));
                    }
                } else {
                    Logger::warn('Failed to trigger "{0}", node with uid = {1} is not found', $event, $uuid);
                }

                break;

            case 'ui-user-input':
                ['uuid' => $uuid, 'data' => $data] = $message->getData();
                $node = $this->findNodeByUuidGlobally($uuid);

                if ($node) {
                    $parseValue = $this->parseValue((array)$data);

                    $node->provideUserInput($parseValue);

                    $this->socket->setExcludeActivated(true);
                    $node->synchronizeUserInput($parseValue);
                    $this->socket->setExcludeActivated(false);
                } else {
                    Logger::warn('Failed to provide user input, node with uid = {1} is not found', $uuid);
                }
                break;

            case 'ui-ready':
                $this->location = $message->getData()['location'];
                $this->renderView();
                $this->trigger(new Event('ready', $this, null, $message->getData()));
                break;

            case "page-change-hash":
                $this->trigger(new Event('changeHash', $this, null, $message->getData()));
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
    public function show(HttpServerRequest $request, HttpServerResponse $response, string $path)
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
        $body = str::replace($body, '{{title}}', $request->attribute('~title'));

        $body = str::replace($body, '{{urlArgument}}', $request->attribute('**'));

        $response->contentType('text/html');
        $response->body($body);
    }

    /**
     * @param string $type
     * @param array $message
     */
    public function sendMessage(string $type, array $message)
    {
        $this->socket->sendText(reflect::typeOf($this), $type, $message);
    }

    /**
     * Render View.
     */
    public function renderView()
    {
        $this->sendMessage('ui-render', [
            'schema' => $this->getUISchema()
        ]);
    }

    /**
     * @param string $message
     */
    public function alert(string $message)
    {
        $this->socket->sendText(reflect::typeOf($this), 'ui-alert', ['text' => $message]);
    }

    /**
     * @param UINode $node
     * @param string $property
     * @param $value
     */
    public function changeNodeProperty(UINode $node, string $property, $value)
    {
        $this->socket->sendText(reflect::typeOf($this), 'ui-set-property', [
            'uuid' => $node->getUuid(),
            'property' => $property,
            'value' => $this->prepareValue($value)
        ]);
    }

    /**
     * @param UINode $node
     * @param string $method
     * @param array $args
     */
    public function callNodeMethod(UINode $node, string $method, array $args = [])
    {
        $args = $this->prepareValue($args);

        $this->socket->sendText(reflect::typeOf($this), 'ui-call-method', [
            'uuid' => $node->getUuid(),
            'method' => $method,
            'args' => $args
        ]);
    }

    /**
     * @param UINode $node
     * @param string $eventType
     */
    public function addEventLink(UINode $node, string $eventType)
    {
        $schemaEvents = $node->uiSchemaEvents();

        if ($new = $schemaEvents[$eventType]) {
            $eventType = $new;
        }

        $this->socket->sendText(reflect::typeOf($this), 'ui-event-link', [
            'uuid' => $node->getUuid(),
            'event' => str::lower($eventType),
        ]);
    }

    /**
     * Setup UI for the current thread.
     *
     * @throws \Exception
     */
    public static function checkAvailable() {
        if (!static::current()) {
            throw new \Exception("UI is not available, specify UI via the UI::setup() method");
        }
    }

    /**
     * @param UI|null $ui
     */
    public static function setup(?UI $ui) {
        if (!static::$current) {
            static::$current = new ThreadLocal($ui);
        } else {
            static::$current->set($ui);
        }
    }


    /**
     * @return UI
     * @throws \Exception
     */
    public static function currentRequired(): UI
    {
        static::checkAvailable();

        return static::current();
    }

    /**
     * @return UI
     */
    public static function current(): ?UI
    {
        if (static::$current && static::$current->get()) {
            return static::$current->get();
        }

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT);

        foreach ($backtrace as $item) {
            $object = $item['object'];

            $ui = null;
            if ($object instanceof UI) {
                $ui = $object;
            } else if ($object instanceof UINode && $object->isConnectedToUI()) {
                $ui = $object->getConnectedUI();
            } else if ($object instanceof UIForm && $object->isConnectedToUI()) {
                $ui = $object->getConnectedUI();
            }

            if ($ui) {
                if (!static::$current) {
                    static::$current = new ThreadLocal($ui);
                } else {
                    static::$current->set($ui);
                }

                return $ui;
            }
        }

        return null;
    }
}