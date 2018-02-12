<?php
namespace framework\core;

/**
 * Class Behaviour
 * @package framework\core
 */
abstract class Behaviour extends Component
{
    /**
     * @var Component
     */
    protected $target;

    /**
     * Behaviour constructor.
     * @param Component $target
     */
    public function __construct(Component $target)
    {
        parent::__construct();

        $this->target = $target;
    }

    /**
     * @return mixed
     */
    abstract public function apply();
}