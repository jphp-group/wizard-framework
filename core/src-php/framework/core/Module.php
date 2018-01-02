<?php
namespace framework\core;

/**
 * Class Module
 * @package framework\core
 */
class Module extends Component
{
    /**
     * @var Module[]
     */
    private $subModules = [];

    /**
     * Module constructor.
     */
    public function __construct()
    {
        $this->loadBinds();
    }

    /**
     * @param Module $module
     */
    public function addModule(Module $module)
    {
        $this->subModules[] = $module;
    }

    /**
     * @return Module[]
     */
    public function getModules(): array
    {
        return $this->subModules;
    }

    /**
     *
     */
    protected function loadBinds()
    {
        $binder = new AnnotationEventBinder($this, $this);
        $binder->loadBinds();
    }
}