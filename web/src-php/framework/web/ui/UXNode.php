<?php
namespace framework\web\ui;

use framework\core\Component;

/**
 * Class UXNode
 * @package framework\web\ui
 *
 * @property string $id
 */
abstract class UXNode extends Component implements UXViewable
{
    /**
     * @var array
     */
    protected $state = [];

    /**
     * @return string
     */
    abstract public function uiSchemaClassName(): string;

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

        return $view;
    }

    /**
     * @param string $name
     * @param $value
     */
    protected function setProperty(string $name, $value)
    {
        $this->state[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed $def
     * @return mixed
     */
    protected function getProperty(string $name, $def = null)
    {
        return $this->state[$name] ?? $def;
    }

    public function getId(): string
    {
        return $this->getProperty('id');
    }

    public function setId(string $id)
    {
        $this->setProperty('id', $id);
    }
}