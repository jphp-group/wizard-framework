<?php

namespace framework\core;

/**
 * Class Event
 * @package framework\core
 *
 * @property Component|object $sender
 * @property Component|object|null $context
 * @property string $type
 * @property array $data
 */
class Event
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var Component|object
     */
    private $sender;

    /**
     * @var bool
     */
    private $consumed = false;

    /**
     * @var Component|object
     */
    private $context;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Event constructor.
     * @param string $type
     * @param object $sender
     * @param object|null $context
     * @param array|null $data
     */
    public function __construct(string $type, object $sender, ?object $context = null, ?array $data = null)
    {
        $this->type = $type;
        $this->sender = $sender;
        $this->context = $context;
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isConsumed(): bool
    {
        return $this->consumed;
    }

    /**
     * Consume event.
     */
    public function consume(): void
    {
        $this->consumed = true;
    }

    /**
     * @param string $name
     * @return object|string|array
     * @throws \Error
     */
    public function __get(string $name)
    {
        switch ($name) {
            case "type":
                return $this->type;
            case "sender":
                return $this->sender;
            case "context":
                return $this->context;
            case "data":
                return $this->data;
        }

        if ($this->data[$name]) {
            return $this->data[$name];
        }

        throw new \Error("Property '$name' is not found");
    }

    public function __isset($name)
    {
        switch ($name) {
            case "type":
                return isset($this->type);
            case "sender":
                return isset($this->sender);
            case "context":
                return isset($this->context);
            case "data":
                return isset($this->data);
        }

        return isset($this->data[$name]);
    }
}