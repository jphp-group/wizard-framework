<?php
namespace framework\core;

/**
 * Class EventSignal
 * @package framework\core
 */
class EventSignal
{
    /**
     * @var Component
     */
    private $component;

    /**
     * @var string
     */
    private $event;

    /**
     * EventSignal constructor.
     * @param Component $component
     * @param string $event
     * @throws \Exception
     */
    public function __construct(Component $component, string $event)
    {
        $this->component = $component;
        $this->event = $event;

        if (!$event) {
            throw new \Exception("event is empty");
        }
    }

    /**
     * @param callable $callback
     * @param string $group
     */
    public function set(callable $callback, string $group = 'general')
    {
        $this->component->on($this->event, $callback, $group);
    }

    /**
     * @param callable $callback
     * @return string
     */
    public function add(callable $callback): string
    {
        return $this->component->bind($this->event, $callback);
    }

    /**
     * @param callable $callback
     * @return string
     */
    public function addOnce(callable $callback): string
    {
        $id = null;
        $id = $this->add(function ($e) use ($callback, &$id) {
            $callback($e);
            $this->component->off($this->event, $id);
        });

        return $id;
    }

    /**
     * @param array|null $data
     * @param string|null $group
     */
    public function trigger(array $data = null, string $group = null)
    {
        $this->component->trigger(new Event($this->event, $this->component, null, $data), $group);
    }
}