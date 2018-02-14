<?php
namespace framework\ide;

use framework\core\Component;

/**
 * @package framework\ide
 *
 * @property string $name
 * @property string $description
 * @property string $className
 * @property IdeComponent $parent
 * @property IdeComponentField[] $fields
 *
 */
class IdeComponent extends Component
{
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
    private $className;

    /**
     * @var IdeComponent|null
     */
    private $parent;

    /**
     * @var IdeComponentField[]
     */
    private $fields = [];

    /**
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    protected function setClassName(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return IdeComponentField[]
     */
    protected function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param IdeComponentField[] $fields
     */
    protected function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return IdeComponent|null
     */
    protected function getParent(): ?IdeComponent
    {
        return $this->parent;
    }

    /**
     * @param IdeComponent|null $parent
     */
    protected function setParent(?IdeComponent $parent)
    {
        $this->parent = $parent;
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
    protected function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    protected function setDescription(string $description)
    {
        $this->description = $description;
    }
}