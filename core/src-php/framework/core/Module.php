<?php
namespace framework\core;

use php\io\Stream;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;

/**
 * Class ContainerComponent
 * @package framework\core
 */
abstract class Module extends Component
{
    /**
     * @var Component[]
     */
    private $children = [];

    /**
     * @var array
     */
    private $childrenById = [];

    /**
     * Module constructor.
     */
    public function __construct()
    {
        $moduleFile = reflect::typeModule(reflect::typeOf($this))->getName();
        $ext = fs::ext($moduleFile);
        $moduleFile = str::sub($moduleFile, 0, str::length($moduleFile) - str::length($ext) - 1) . ".module";

        if (Stream::exists($moduleFile)) {
            $componentLoader = new ComponentLoader();
            $components = $componentLoader->load($moduleFile);

            foreach ($components as $component) {
                $this->addComponent($component);
            }
        }

        $eventBinder = new EventBinder($this);
        $eventBinder->apply();
    }

    /**
     * @param Component $component
     */
    public function addComponent(Component $component)
    {
        $this->children[] = $component;

        if ($component->id) {
            $this->childrenById[$component->id] = $component;
        }

        $component->trigger(new Event('register', $component, $this));
    }

    /**
     * @return Component[]
     */
    public function components()
    {
        return $this->children;
    }

    /**
     * @param string $name
     * @return bool|mixed|Component
     */
    public function __get(string $name)
    {
        if ($component = $this->childrenById[$name]) {
            return $component;
        }

        return parent::__get($name);
    }
}