<?php
namespace framework\web\ui\animations;

use framework\core\Component;
use framework\core\Event;
use framework\web\ui\UINode;

/**
 * Class UIAnimation
 * @package framework\web\ui\animations
 *
 * @property mixed $duration
 * @property mixed $delay
 * @property string $when
 */
abstract class UIAnimationComponent extends Component
{
    /**
     * @var mixed
     */
    private $delay = 0;

    /**
     * @var mixed
     */
    private $duration = '1s';

    /**
     * render, hover, click
     * @var string
     */
    private $when = 'render';

    /**
     * @var EventSignal
     */
    public $onComplete;

    /**
     * UIAnimation constructor.
     * @param mixed $duration
     * @param mixed $delay
     * @param string $when
     */
    public function __construct($duration = '1s', $delay = 0, string $when = 'render')
    {
        parent::__construct();

        $this->duration = $duration;
        $this->delay = $delay;
        $this->when = $when;
    }


    /**
     * @param UINode $node
     * @return mixed
     */
    private function animate(UINode $node)
    {
        $this->trigger(new Event('animate', $this, $node));
    }

    /**
     * @param UINode $node
     */
    private function reverseAnimate(UINode $node)
    {
        $this->trigger(new Event('reverseAnimate', $this, $node));
    }

    /**
     * @event addTo
     * @param Event $e
     */
    public function handleAddTo(Event $e)
    {
        $owner = $e->context;

        if ($owner instanceof UINode) {
            switch ($this->when) {
                case 'render':
                    $owner->bind('render', function () use ($owner) {
                        $this->animate($owner);
                    });

                    break;

                case 'click':
                    $clickCount = 0;
                    $owner->bind('click', function () use ($owner, &$clickCount) {
                        if ($clickCount % 2 == 0) {
                            $this->animate($owner);
                        } else {
                            $this->reverseAnimate($owner);
                        }

                        $clickCount += 1;
                    });
                    break;

                case 'hover':
                    $owner->bind('mouseEnter', function () use ($owner) {
                        $this->animate($owner);
                    });
                    $owner->bind('mouseLeave', function () use ($owner) {
                        $this->reverseAnimate($owner);
                    });
                    break;
            }
        }
    }

    public function handleRemoveFrom()
    {

    }

    /**
     * @return mixed
     */
    protected function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    protected function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    protected function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param mixed $delay
     */
    protected function setDelay($delay)
    {
        $this->delay = $delay;
    }

    /**
     * @return string
     */
    protected function getWhen(): string
    {
        return $this->when;
    }

    /**
     * @param string $when
     */
    protected function setWhen(string $when)
    {
        $this->when = $when;
    }
}