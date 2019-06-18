<?php

namespace framework\web\ui;

use framework\core\Component;
use framework\core\Event;
use framework\core\EventSignal;
use framework\core\Logger;
use framework\web\UI;
use php\lib\arr;
use php\lib\reflect;
use php\lib\str;
use php\time\Timer;

/**
 * @package framework\web\ui
 *
 * @property string $id
 * @property string $uuid
 * @property string $style
 * @property array|string $classes
 * @property mixed $width
 * @property mixed $height
 * @property array $size
 * @property int $x
 * @property int $y
 * @property int[] $position
 * @property float $opacity
 * @property UIContainer $parent
 *
 * @property bool $enabled
 * @property bool $visible
 * @property string|UINode $tooltip
 * @property array $tooltipOptions
 */
abstract class UINode extends Component implements UIViewable
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $style = '';

    /**
     * @var array
     */
    private $classes = [];

    /**
     * @var string|int
     */
    private $width;

    /**
     * @var string|int
     */
    private $height;

    /**
     * @var int
     */
    private $x = 0;

    /**
     * @var int
     */
    private $y = 0;

    /**
     * @var bool
     */
    private $visible = true;

    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @var bool
     */
    private $selectionEnabled = true;

    /**
     * @var string
     */
    private $tooltip = '';

    /**
     * @var array
     */
    private $tooltipOptions = [];

    /**
     * @var array
     */
    private $padding = [0, 0, 0, 0];

    /**
     * @var string
     */
    private $cursor = 'default';

    /**
     * @var UI
     */
    protected $connectedUi;

    /**
     * @var UIContainer
     */
    private $parent;

    /**
     * @var EventSignal
     */
    public $onClick;

    /**
     * @var EventSignal
     */
    public $onMouseDown;

    /**
     * @var EventSignal
     */
    public $onMouseUp;

    /**
     * @var EventSignal
     */
    public $onKeyDown;

    /**
     * @var EventSignal
     */
    public $onKeyUp;

    /**
     * @var EventSignal
     */
    public $onKeyPress;

    /**
     * @return string
     */
    abstract public function uiSchemaClassName(): string;

    public function uiSchemaEvents(): array
    {
        return [];
    }

    /**
     * UXNode constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->uuid = 'n' . str::uuid();
    }

    /**
     * @return array
     */
    public function uiSchema(): array
    {
        $view = ['_' => $this->uiSchemaClassName()];

        $class = new \ReflectionClass($this);
        $defaultProperties = $class->getDefaultProperties();

        foreach ($this->getProperties() as $name => $value) {
            if ($name === 'parent' || $name === 'connectedUi' || $name === 'components') continue;

            if ($value instanceof UIViewable) {
                $value = $value->uiSchema();
            }

            if (isset($defaultProperties[$name])) {
                $default = $defaultProperties[$name];

                if ($default === $value) {
                    continue;
                }
            }

            $view[$name] = $value;
        }

        $view['_watchedEvents'] = [];

        foreach ($this->eventHandlers as $event => $handlers) {
            $view['_watchedEvents'][] = str::lower($event);
        }

        return $view;
    }

    public function __set(string $name, $value)
    {
        $this->changeRemoteProperty($name, $value);
        parent::__set($name, $value);
    }

    /**
     * @return string
     */
    protected function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    protected function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    protected function getStyle(): string
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    protected function setStyle(string $style)
    {
        $this->style = $style;
    }

    /**
     * @return array|string
     */
    protected function getClasses(): array
    {
        return (array) $this->classes;
    }

    /**
     * @param array|string $classes
     */
    protected function setClasses($classes)
    {
        $this->classes = is_array($classes) ? $classes : str::split(' ', $classes);
    }

    /**
     * @return int|string
     */
    protected function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int|string $width
     */
    protected function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int|string
     */
    protected function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int|string $height
     */
    protected function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return array
     */
    protected function getSize(): array
    {
        return [$this->width, $this->height];
    }

    /**
     * @param array $size
     */
    protected function setSize(array $size)
    {
        if (sizeof($size) >= 2) {
            $this->width = $size[0];
            $this->height = $size[1];

            $this->changeRemoteProperty('width', $this->width);
            $this->changeRemoteProperty('height', $this->height);
        }
    }

    /**
     * @return int
     */
    protected function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    protected function setX(int $x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    protected function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    protected function setY(int $y)
    {
        $this->y = $y;
    }

    /**
     * @return array
     */
    protected function getPosition(): array
    {
        return [$this->x, $this->y];
    }

    /**
     * @param int[] $position
     */
    protected function setPosition(array $position)
    {
        if (sizeof($position) >= 2) {
            $this->x = $position[0];
            $this->y = $position[1];

            $this->changeRemoteProperty('x', $this->x);
            $this->changeRemoteProperty('y', $this->y);
        }
    }

    /**
     * @return bool
     */
    protected function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    protected function setVisible(bool $visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return bool
     */
    protected function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    protected function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    protected function isSelectionEnabled(): bool
    {
        return $this->selectionEnabled;
    }

    /**
     * @param bool $selectionEnabled
     */
    protected function setSelectionEnabled(bool $selectionEnabled)
    {
        $this->selectionEnabled = $selectionEnabled;
    }

    /**
     * @return float
     */
    protected function getOpacity(): ?float
    {
        return $this->css('opacity');
    }

    /**
     * @param float $opacity
     */
    protected function setOpacity(?float $opacity)
    {
        $this->css(['opacity' => $opacity]);
    }

    /**
     * @return array
     */
    protected function getPadding(): array
    {
        return $this->padding;
    }

    /**
     * @param array|mixed $padding
     */
    protected function setPadding($padding)
    {
        if (!is_array($padding)) {
            $padding = [$padding, $padding, $padding, $padding];
        } else {
            switch (sizeof($padding)) {
                case 0:
                    $padding = [0, 0, 0, 0];
                    break;

                case 1:
                    $padding = [$padding[0], $padding[0], $padding[0], $padding[0]];
                    break;

                case 2:
                    $padding = [$padding[0], $padding[1], $padding[0], $padding[1]];
                    break;

                case 3:
                    $padding = [$padding[0], $padding[1], $padding[2], $padding[1]];
                    break;

                case 4:
                    break;

                default:
                    $padding = [$padding[0], $padding[1], $padding[2], $padding[3]];
                    break;
            }
        }

        $this->padding = $padding;
    }

    /**
     * @return string|UINode
     */
    protected function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param string|UINode $tooltip
     */
    protected function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    /**
     * @return array
     */
    protected function getTooltipOptions(): array
    {
        return $this->tooltipOptions;
    }

    /**
     * @param array $tooltipOptions
     */
    protected function setTooltipOptions(array $tooltipOptions)
    {
        $this->tooltipOptions = $tooltipOptions;
    }

    /**
     * @return string
     */
    protected function getCursor(): string
    {
        return $this->cursor;
    }

    /**
     * @param string $cursor
     */
    protected function setCursor(string $cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * @return UIContainer
     */
    protected function getParent(): ?UIContainer
    {
        return $this->parent;
    }

    /**
     * @param UIContainer|null $parent
     */
    public function __setParent(?UIContainer $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return UI|null
     */
    public function getConnectedUI(): ?UI
    {
        return $this->connectedUi;
    }

    /**
     * @return bool
     */
    public function isConnectedToUI(): bool
    {
        return !!$this->connectedUi;
    }

    /**
     * @param UI $ui
     */
    public function connectToUI(?UI $ui)
    {
        $this->connectedUi = $ui;
    }

    public function disconnectUI()
    {
        $this->connectedUi = null;
    }

    public function toFront()
    {
        $this->callRemoteMethod(__FUNCTION__);
    }

    public function toBack()
    {
        $this->callRemoteMethod(__FUNCTION__);
    }

    public function hide()
    {
        $this->visible = false;
        $this->changeRemoteProperty('visible', false);
    }

    public function show()
    {
        $this->visible = true;
        $this->changeRemoteProperty('visible', true);
    }

    public function toggle()
    {
        if ($this->visible) {
            $this->hide();
        } else {
            $this->show();
        }
    }

    /**
     * @param string|array $style
     * @param null|string $idStyle
     * @param callable|null $callback
     * @return null|string
     */
    public function addCssStyle($style, ?string $idStyle = null, ?callable $callback = null): ?string
    {
        if (is_iterable($style)) {
            $style = flow($style)->reduce(function ($result, $value, $key) {
                $result .= "$key:$value;";
                return $result;
            });
        }

        if ($this->isConnectedToUI()) {
            return $this->getConnectedUI()->createCssStyle(".{$this->uuid} { \n" . $style . "\n }", $idStyle, $callback);
        } else {
            Logger::warn("Failed to {0}::addCssStyle(...), ui is not connected", reflect::typeOf($this));
        }

        return null;
    }

    /**
     * @param string $idStyle
     * @return string
     */
    public function removeCssStyle(string $idStyle)
    {
        if ($this->isConnectedToUI()) {
            $this->getConnectedUI()->destroyCssStyle($idStyle);
        } else {
            Logger::warn("Failed to {0}::removeCssStyle(...), ui is not connected", reflect::typeOf($this));
        }
    }

    /**
     * @param array|string $style
     * @return null|string
     */
    public function css($style)
    {
        $oldStyle = $this->style;

        if (is_array($style)) {
            $css = flow(str::split($oldStyle, ';'))->reduce(function ($result, $el) {
                if (!$result) {
                    $result = [];
                }

                [$prop, $value] = str::split($el, ':');

                $prop = str::trim($prop);
                $value = str::trim($value);

                $result[$prop] = $value;
                return $result;
            });

            foreach ($style as $prop => $value) {
                if ($value === null) {
                    unset($css[$prop]);
                } else {
                    $css[$prop] = $value;
                }
            }

            $style = flow($css)->reduce(function ($result, $value, $key) {
                if ($value === null) return $result;

                $result .= "$key:$value;";
                return $result;
            });

            $this->style = $style;
            $this->callRemoteMethod('css', [$css]);
        } else {
            $result = flow(str::split($oldStyle, ';'))
                ->map(function ($el) {
                    return str::split($el, ':');
                })
                ->findOne(function ($el) use ($style) {
                    return (trim($el[0]) === $style);
                });

            return $result ? $result[1] : null;
        }
    }

    /**
     * @param int|string $duration
     * @param float $opacity
     * @param callable|null $complete
     * @param string $queue
     */
    public function fadeTo($duration, float $opacity, callable $complete = null, string $queue = 'general')
    {
        $this->animate(['opacity' => $opacity], ['duration' => $duration, 'complete' => function () use ($opacity, $complete) {
            $this->provideUserInput(['opacity' => $opacity]);

            if ($complete) {
                $complete();
            }
        }], $queue);
    }

    /**
     * @param int|string $duration
     * @param callable|null $complete
     * @param string $queue
     */
    public function fadeIn($duration = 400, callable $complete = null, string $queue = 'general')
    {
        $this->fadeTo($duration, 1.0, $complete, $queue);
    }

    /**
     * @param int|string $duration
     * @param callable|null $complete
     * @param string $queue
     */
    public function fadeOut($duration = 400, callable $complete = null, string $queue = 'general')
    {
        $this->fadeTo($duration, 0.0, $complete, $queue);
    }

    /**
     * @param array $properties
     * @param array $options
     * @param null|string $queue
     */
    public function animate(array $properties, array $options, ?string $queue = 'general')
    {
        if (isset($options['duration'])) {
            $options['duration'] = Timer::parsePeriod($options['duration']);
        }

        //$options['queue'] = $queue;

        $this->callRemoteMethod('animate', [$properties, $options]);
    }

    public function stopAllAnimate(bool $clearQueue = false, bool $jumpToEnd = false, ?callable $callback = null)
    {
        $this->callRemoteMethod('stopAllAnimate', [$clearQueue, $jumpToEnd, $callback]);
    }

    /**
     * @param bool $clearQueue
     * @param bool $jumpToEnd
     * @param null|string $queue
     * @param callable|null $callback
     */
    public function stopAnimate(bool $clearQueue = false, bool $jumpToEnd = false, string $queue = 'general', ?callable $callback = null)
    {
        $this->callRemoteMethod('stopAnimate', [$queue, $clearQueue, $jumpToEnd, $callback]);
    }

    public function free()
    {
        if (!$this->isFree()) {
            $this->trigger(new Event('free', $this));

            if ($this->parent) {
                $this->parent->remove($this);
            }

            $this->callRemoteMethod('free');
        }
    }

    public function isFree()
    {
        return !!$this->parent;
    }

    /**
     * @return array
     */
    public function innerNodes(): array
    {
        if ($this->tooltip instanceof UINode) {
            return [$this->tooltip];
        }

        return [];
    }

    public function on(string $eventType, callable $handler, string $group = "general")
    {
        parent::on($eventType, $handler, $group);

        $this->addEventLink(str::lower($eventType));
    }

    protected function addEventLink($eventType)
    {
        if ($this->connectedUi) {
            $this->connectedUi->addEventLink($this, $eventType);
        }
    }

    /**
     * @param string $method
     * @param array $args
     * @param bool $waitConnect
     */
    public function callRemoteMethod(string $method, array $args = [], bool $waitConnect = false)
    {
        if ($this->connectedUi) {
            $this->connectedUi->callNodeMethod($this, $method, $args);
        } else {
            if ($waitConnect) {
                Timer::after(100, function () use ($method, $args) {
                    $this->callRemoteMethod($method, $args, true);
                });
            } else {
                Logger::warn("Failed to {0}::callRemoteMethod({1}, args), ui is not connected", reflect::typeOf($this), $method);
            }
        }
    }

    /**
     * @param string $property
     * @param $value
     */
    public function changeRemoteProperty(string $property, $value)
    {
        if ($this->connectedUi) {
            $this->connectedUi->changeNodeProperty($this, $property, $value);
        } else {
            /*Logger::debug(
                "Unable to change {0} property for {1} ({2}), UI is disconnected.", $property,
                reflect::typeOf($this),
                $this->uuid
            );*/
        }
    }

    public function provideUserInput(array $data)
    {
        $this->provideUserInputProperties(['visible', 'enabled'], $data);
    }

    protected function provideUserInputProperties(array $props, array $data)
    {
        foreach ($props as $prop) {
            if (isset($data[$prop])) {
                $this->__set($prop, $data[$prop]);
            }
        }
    }

    public function synchronizeUserInput(array $data)
    {
        // nop.
    }


    public function __clone()
    {
        $this->uuid = str::uuid();
    }
}