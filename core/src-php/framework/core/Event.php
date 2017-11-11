<?php
namespace framework\core;

/**
 * Class Event
 * @package framework\core
 *
 * @property Component $sender
 * @property string $type
 */
class Event
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var Component
     */
    private $sender;

    /**
     * @var bool
     */
    private $consumed = false;

    /**
     * Event constructor.
     * @param string $type
     * @param Component $sender
     */
    public function __construct(string $type, Component $sender)
    {
        $this->type = $type;
        $this->sender = $sender;
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
     * @return object|string
     * @throws \Error
     */
    public function __get(string $name)
    {
        switch ($name) {
            case "type": return $this->type;
            case "sender": return $this->sender;
        }

        throw new \Error("Property '$name' is not found");
    }
}