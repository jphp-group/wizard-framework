<?php

namespace framework\web\ui;

use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use framework\web\UI;
use php\lib\arr;
use php\lib\reflect;
use php\lib\str;

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
     * @var string
     */
    private $tooltip = '';

    /**
     * @var array
     */
    private $tooltipOptions = [];

    /**
     * @var UI
     */
    protected $connectedUi;

    /**
     * @var UIContainer
     */
    private $parent;

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
        $this->uuid = str::uuid();
    }

    /**
     * @return array
     */
    public function uiSchema(): array
    {
        $view = ['_' => $this->uiSchemaClassName()];

        foreach ($this->getProperties() as $name => $value) {
            if ($name === 'parent' || $name === 'connectedUi') continue;

            if ($value instanceof UIViewable) {
                $value = $value->uiSchema();
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
    public function setVisible(bool $visible)
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
     * @return string|UINode
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param string|UINode $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    /**
     * @return array
     */
    public function getTooltipOptions(): array
    {
        return $this->tooltipOptions;
    }

    /**
     * @param array $tooltipOptions
     */
    public function setTooltipOptions(array $tooltipOptions)
    {
        $this->tooltipOptions = $tooltipOptions;
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

    public function on(string $eventType, callable $handler, string $group = 'general')
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
     * @param string$method
     * @param array $args
     */
    public function callRemoteMethod(string $method, array $args = [])
    {
        if ($this->connectedUi) {
            $this->connectedUi->callNodeMethod($this, $method, $args);
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
        if (isset($data['visible'])) {
            $this->setVisible($data['visible']);
        }

        if (isset($data['enabled'])) {
            $this->setEnabled($data['enabled']);
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