<?php
namespace framework\web\ui\animations;

use framework\core\Event;
use framework\core\Logger;
use framework\web\ui\UINode;

/**
 * Class UIFadeAnimation
 * @package framework\web\ui\animations
 *
 * @property float $delay
 */
class UIFadeAnimation extends UIAnimationComponent
{
    /**
     * @var float
     */
    private $opacity = 0.5;

    /**
     * @var float
     */
    private $initialOpacity = 1.0;

    /**
     * UIFadeAnimation constructor.
     * @param float $opacity
     * @param string $duration
     * @param int $delay
     * @param string $when
     */
    public function __construct(float $opacity = 0.5, $duration = '1s', $delay = 0, $when = 'render')
    {
        parent::__construct($duration, $delay, $when);

        $this->opacity = $opacity;
    }

    /**
     * @event animate
     */
    public function handleAnimate()
    {
        $this->initialOpacity = $this->owner->opacity;

        $this->owner->stopAllAnimate(true, false, function () {
            $this->owner->fadeTo($this->duration, $this->opacity, function () {
                $this->trigger(new Event('complete', $this, $this->owner));
            }, __CLASS__);
        });
    }

    /**
     * @event reverseAnimate
     * @param Event $e
     */
    public function handleReverseAnimate(Event $e)
    {
        /** @var UINode $node */
        $node = $this->owner;

        $node->stopAllAnimate(true, false, function () use ($node) {
            $node->fadeTo($this->duration, $this->initialOpacity, null, __CLASS__);
        });
    }

    /**
     * @return float
     */
    protected function getOpacity(): float
    {
        return $this->opacity;
    }

    /**
     * @param float $opacity
     */
    protected function setOpacity(float $opacity)
    {
        $this->opacity = $opacity;
    }
}