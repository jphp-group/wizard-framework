<?php
namespace framework\core;

/**
 * Class ContainerComponent
 * @package framework\core
 */
abstract class ContainerComponent extends Component
{
    /**
     * @var Component[]
     */
    private $children = [];

    /**
     * @param Component $component
     */
    public function addComponent(Component $component)
    {
        $this->children[] = $component;
        $component->trigger(new Event('register', $this));
    }

    /**
     * @return Component[]
     */
    public function components()
    {
        return $this->children;
    }
}