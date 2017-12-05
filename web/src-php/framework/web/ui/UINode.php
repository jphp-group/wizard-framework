<?php

namespace framework\web\ui;

use framework\core\Component;
use framework\core\Event;
use framework\web\UI;
use php\lib\str;

/**
 * @package framework\web\ui
 *
 * @property string $id
 * @property string $uuid
 * @property mixed $width
 * @property mixed $height
 * @property array $size
 * @property UIContainer $parent
 *
 * @property bool $enabled
 * @property bool $visible
 */
abstract class UINode extends Component implements UIViewable
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
     * @return UIContainer
     */
    public function getParent(): ?UIContainer
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