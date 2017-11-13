<?php
namespace framework\core;

use php\lib\reflect;
use php\lib\str;

/**
 * Class Component
 * @package framework\core
 */
abstract class Component
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    protected $eventHandlers = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $name
     * @param null|mixed $value
     * @return mixed
     */
    public function data(string $name, $value = null)
    {
        if (func_num_args() == 1) {
            return $this->data[$name];
        } else {
            $oldValue = $this->data[$name];
            $this->data[$name] = $value;

            return $oldValue;
        }
    }

    /**
     * @param $eventType
     * @param callable $handler
     * @param string $group
     */
    public function on(string $eventType, callable $handler, string $group = 'general')
    {
        $this->eventHandlers[$eventType][$group] = $handler;
    }

    /**
     * @param string $eventType
     * @param callable $handler
     * @return string
     */
    public function bind(string $eventType, callable $handler): string
    {
        $this->on($eventType, $handler, $group = str::uuid());
        return $group;
    }

    /**
     * @param string $eventType
     * @param null|string $group
     */
    public function off(string $eventType, ?string $group = null)
    {
        if ($group) {
            unset($this->eventHandlers[$eventType][$group]);
        } else {
            unset($this->eventHandlers[$eventType]);
        }
    }

    /**
     * @param Event $e
     * @param null|string $group
     * @internal param string $eventType
     */
    public function trigger(Event $e, ?string $group = null)
    {
        $eventType = $e->type;

        if ($group) {
            if ($handler = $this->eventHandlers[$eventType][$group]) {
                $handler($e);
            }
        } else {
            foreach ((array) $this->eventHandlers[$eventType] as $group => $handler) {
                $handler($e);

                if ($e->isConsumed()) {
                    break;
                }
            }
        }
    }

    public function __get(string $name)
    {
        $method = "get$name";

        if (method_exists($this, $method)) {
            return $method();
        }

        $method = "is$name";
        if (method_exists($this, $method)) {
            return (bool) $method();
        }

        throw new \Error("Property '$name' is not exists in class " . reflect::typeOf($this));
    }

    public function __set(string $name, $value)
    {
        $method = "set$name";

        if (method_exists($this, $method)) {
            return $method($value);
        }

        throw new \Error("Property '$name' is not exists in class or readonly" . reflect::typeOf($this));
    }
}