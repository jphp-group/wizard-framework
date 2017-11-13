<?php
namespace framework\core;

/**
 * Class Event
 * @package framework\core
 *
 * @property Component $sender
 * @property Component|null $context
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
     * @var Component|null
     */
    private $context;

    /**
     * Event constructor.
     * @param string $type
     * @param Component $sender
     * @param Component|null $context
     */
    public function __construct(string $type, Component $sender, ?Component $context = null)
    {
        $this->type = $type;
        $this->sender = $sender;
        $this->context = $context;
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
            case "context": return $this->context;
        }

        throw new \Error("Property '$name' is not found");
    }
}