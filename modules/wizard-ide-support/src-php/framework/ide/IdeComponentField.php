<?php
namespace framework\ide;

use framework\core\Component;

/**
 * @package framework\ide
 *
 * @property array $type
 * @property string $name
 * @property string $default
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

    /**
     * @param string $name
     */
    protected function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    protected function getDefault(): ?string
    {
        return $this->default;
    }

    /**
     * @param string $default
     */
    protected function setDefault(?string $default)
    {
        $this->default = $default;
    }
    
}