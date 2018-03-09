<?php
namespace framework\ide;

use framework\core\Component;

/**
 * @package framework\ide
 *
 * @property array $type
 * @property string $name
 * @property string $localizedName
 * @property string $description
 * @property string $localizedDescription
 * @property mixed $default
 */
class IdeComponentField extends Component
{
    /**
     * @var array
     */
    private $type = ['name' => 'mixed', 'nullable' => true, 'builtin' => true];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $default;

    /**
     * @return array
     */
    protected function getType(): array
    {
        return $this->type;
    }

    /**
     * @param array $type
     */
    protected function setType(array $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    protected function getLocalizedName(): string
    {
        if ($this->owner instanceof IdeComponent) {
            return $this->owner->localizer->translate($this->name);
        } else {
            return $this->name;
        }
    }

    /**
     * @param string $name
     */
    protected function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    protected function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     */
    protected function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    protected function getDescription(): string
    {
        return $this->description;
    }

    protected function getLocalizedDescription(): string
    {
        return $this->owner instanceof IdeComponent ? $this->owner->localizer->translate($this->description) : $this->description;
    }

    /**
     * @param string $description
     */
    protected function setDescription(string $description)
    {
        $this->description = $description;
    }
}