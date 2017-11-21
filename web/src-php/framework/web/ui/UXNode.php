<?php

namespace framework\web\ui;

use framework\core\Component;
use framework\web\UI;
use php\lib\str;

/**
 * Class UXNode
 * @package framework\web\ui
 *
 * @property string $id
 * @property string $uuid
 * @property mixed $width
 * @property mixed $height
 *
 */
abstract class UXNode extends Component implements UXViewable
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string|int
     */
    private $width;

    /**
     * @var string|int
     */
    private $height;

    /**
     * @var bool
     */
    private $visible = true;

    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @var UI
     */
    protected $connectedUi;

    /**
     * @return string
     */
    abstract public function uiSchemaClassName(): string;


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
            if ($value instanceof UXViewable) {
                $value = $value->uiSchema();
            }

            $view[$name] = $value;
        }

        $view['_watchedEvents'] = [];

        foreach ($this->eventHandlers as $event => $handlers) {
            $view['_watchedEvents'][] = $event;
        }

        return $view;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return int|string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int|string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int|string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int|string $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function __set(string $name, $value)
    {
        $this->changeRemoteProperty($name, $value);
        parent::__set($name, $value);
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
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
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param UI $ui
     */
    public function connectToUI(?UI $ui)
    {
        $this->connectedUi = $ui;
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

    public function on(string $eventType, callable $handler, string $group = 'general')
    {
        parent::on($eventType, $handler, $group);

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
        }
    }
}