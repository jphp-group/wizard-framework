<?php

namespace framework\web;

use framework\core\Annotations;
use framework\core\Application;
use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use framework\web\ui\UINode;
use framework\web\ui\UIViewable;
use framework\web\ui\UIWindow;
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
     * @var callable[]
     */
    private $callbacks = [];

    /**
     * @var UISocket
     */
    protected $socket;

    /**
     * @var ThreadLocal
     */
    private static $current;

    /**
     * @var callable
     */
    private $alertFunction;

    /**
     * UI constructor.
     * @param UISocket $socket
     */
    public function __construct(?UISocket $socket = null)
    {
        parent::__construct();

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
     * @param $uuid
     * @return UIWindow|null
     */
    public function findWindow(string $uuid): ?UIWindow
    {
        return $this->windows[$uuid];
    }

    /**
     * @param string $style
     * @param string|null $idStyle
     * @param callable|null $callback
     * @return string
     */
    public function createCssStyle(string $style, string $idStyle = null, ?callable $callback = null): string
    {
        $idStyle = $idStyle ?? str::uuid();

        $this->sendMessage('ui-create-css-style', ['id' => $idStyle, 'style' => $style], $callback);

        return $idStyle;
    }

    /**
     * @param string $idStyle
     * @param callable|null $callback
     */
    public function destroyCssStyle(string $idStyle, ?callable $callback = null)
    {
        $this->sendMessage('ui-destroy-css-style', ['id' => $idStyle], $callback);
    }

    /**
     * @param UIWindow $window
     */
    public function addWindow(UIWindow $window)
    {
        $event = new Event('addWindow', $this, $window);
        $this->trigger($event);

        if (!$event->isConsumed()) {
            $this->windows[$window->uuid] = $window;
            $window->connectToUI($this);

            $this->sendMessage('ui-create-node', ['schema' => $window->uiSchema()]);
        }
    }

    /**
     * @param UIWindow $window
     */
    public function removeWindow(UIWindow $window)
    {
        $event = new Event('removeWindow', $this, $window);
        $this->trigger($event);

        if (!$event->isConsumed()) {
            $window->disconnectUI();
            unset($this->windows[$window->uuid]);
        }
    }

    /**
     * @param callable|null $function
     */
    public function setAlertFunction(?callable $function)
    {
        $this->alertFunction = $function;
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
        return $this->getView()->uiSchema();
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
        if (is_callable($value)) {
            $uuid = str::uuid();
            $this->callbacks[$uuid] = $value;

            return ['$callable' => $uuid];
        } else if (is_array($value)) {
            foreach ($value as &$one) {
                $one = $this->prepareValue($one);
            }

            return $value;
        } else if ($value instanceof UINode) {
            if ($this->findNodeByUuidGlobally($value->uuid)) {
                return ['$node' => $value->uuid];
            } else {
                return ['$createNode' => $value->uiSchema()];
            }
        } else if (is_object($value)) {
            if ($value instanceof UIViewable) {
                $value = $value->uiSchema();
            } else {
                return $this->prepareValue((array)$value);
            }
        }

        return $value;
    }

    public function findNodeByUuidGlobally(string $uuid): ?UINode
    {
        $found = $this->findNodeByUuid($uuid, $this->getView());

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
            if ($view->uuid === $uuid) {
                return $view;
            }

            foreach ($view->innerNodes() as $child) {
                if ($found = $this->findNodeByUuid($uuid, $child)) {
                    return $found;
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

        $needCallback = (bool) $message->getData()['needCallback'];

        switch ($message->getType()) {
            case "callback-trigger":
                ['uuid' => $uuid, 'args' => $args] = $message->getData();

                if ($callback = $this->callbacks[$uuid]) {
                    try {
                        if (is_callable($callback)) {
                            $args = (array)$args;

                            $callback(...$args);
                        }
                    } finally {
                        unset($this->callbacks[$uuid]);
                    }
                }

                break;

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
                    Logger::warn('Failed to provide user input, node with uid = "{0}" is not found', $uuid);
                }
                break;

            case 'ui-ready':
                $this->location = $message->getData()['location'];
                $this->trigger(new Event('ready', $this, null, $message->getData()));
                $this->renderView();
                break;

            case 'ui-render-done':
                $this->trigger(new Event('rendered', $this, null, $message->getData()));
                break;

            case "page-change-hash":
                $this->trigger(new Event('changeHash', $this, null, $message->getData()));
                break;

            default:
                Logger::warn('Unknown socket message (type = {0})', $message->getType());
                break;
        }

        if ($needCallback) {
            $this->sendMessage('system-callback', ['id' => $message->getId()]);
        }
    }

    public function makeHtmlView(string $path, string $jsAppDispatcher, array $resources = [], array $args = []): string
    {
        $moduleFile = reflect::typeModule(__CLASS__)->getName();
        $ext = fs::ext($moduleFile);
        $moduleFile = str::sub($moduleFile, 0, str::length($moduleFile) - str::length($ext) - 1) . ".html";

        $body = fs::get($moduleFile);

        foreach ($args as $name => $value) {
            $body = str::replace($body, "{\{$name\}}", $value);
        }

        $prefix = $args['prefix'] ?? '/';

        $body = str::replace($body, '{{dnextBootstrapCSSUrl}}', $prefix . 'dnext/bootstrap4/bootstrap.min.css');
        $body = str::replace($body, '{{dnextBootstrapJSUrl}}', $prefix. 'dnext/bootstrap4/bootstrap.min.js');
        $body = str::replace($body, '{{dnextBootstrapPopperJSUrl}}', $prefix . 'dnext/bootstrap4/popper.min.js');

        $body = str::replace($body, '{{dnextJqueryJSUrl}}', $prefix . 'dnext/jquery/jquery-3.2.1.min.js');

        $body = str::replace($body, '{{dnextCSSUrl}}', $prefix . 'dnext/engine-' . Application::current()->getStamp() . '.min.css');
        $body = str::replace($body, '{{dnextFontCSSUrl}}', $prefix . 'dnext/material-icons/material-icons.css');
        $body = str::replace($body, '{{dnextJSUrl}}', $prefix . 'dnext/engine-' . Application::current()->getStamp() . '.js');

        $body = str::replace($body, '{{uiSchemaUrl}}', "$path/@ui-schema");
        $body = str::replace($body, '{{uiSocketUrl}}', "$path/@ws/");
        $body = str::replace($body, '{{jsAppDispatcher}}', $jsAppDispatcher);


        $head = [];
        foreach ($resources as $resource) {
            $ext = fs::ext($resource);

            switch ($ext) {
                case "js":
                    $head[] = "<script type='text/javascript' src='$resource'></script>";
                    break;
                case "css":
                    $head[] = "<link rel='stylesheet' href='$resource'>";
                    break;
            }
        }

        $body = str::replace($body, '{{head}}', str::join($head, "\n"));

        return $body;
    }

    /**
     * @param string $jsScript
     * @param callable|null $callback
     */
    public function executeScript(string $jsScript, callable $callback = null)
    {
        $this->sendMessage('system-eval', ['script' => $jsScript, 'callback' => $callback]);
    }

    /**
     * @param string $type
     * @param array $message
     * @param callable|null $callback
     */
    public function sendMessage(string $type, array $message, callable $callback = null)
    {
        $this->socket->sendText(reflect::typeOf($this), $type, $this->prepareValue($message), $callback);
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
     * @param array $options
     */
    public function alert(string $message, array $options = [])
    {
        if ($this->alertFunction) {
            call_user_func($this->alertFunction, $message, $options);
        } else {
            $this->socket->sendText(reflect::typeOf($this), 'ui-alert', ['text' => $message, 'options' => $options]);
        }
    }

    /**
     * @param UINode $node
     * @param string $property
     * @param $value
     */
    public function changeNodeProperty(UINode $node, string $property, $value)
    {
        $this->socket->sendText(reflect::typeOf($this), 'ui-set-property', [
            'uuid' => $node->uuid,
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
            'uuid' => $node->uuid,
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
            'uuid' => $node->uuid,
            'event' => str::lower($eventType),
        ]);
    }

    /**
     * Setup UI for the current thread.
     *
     * @throws \Exception
     */
    public static function checkAvailable()
    {
        if (!static::current()) {
            throw new \Exception("UI is not available, specify UI via the UI::setup() method");
        }
    }

    /**
     * @param UI|null $ui
     */
    public static function setup(?UI $ui)
    {
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