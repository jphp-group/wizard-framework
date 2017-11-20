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
     * @var array
     */
    protected $state = [];

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
        if ($this->connectedUi) {
            $this->connectedUi->changeNodeProperty($this, $name, $value);
        }

        parent::__set($name, $value);
    }

    /**
     * @param UI $ui
     */
    public function connectToUI(?UI $ui)
    {
        $this->connectedUi = $ui;
    }
}