<?php
namespace framework\web\ui\animations;

use framework\core\Component;
use framework\core\Event;
use framework\web\ui\UINode;
use php\lib\str;

/**
 * Class UIAnimation
 * @package framework\web\ui\animations
 *
 * @property UINode $owner
 * @property mixed $duration
 * @property mixed $delay
 * @property string $when
 * @property bool $loop
 * @property bool $reverseAnimated
 */
abstract class UIAnimationComponent extends Component
{
    /**
     * @var string
     */
    protected $idStyle;

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
     * @var bool
     */
    private $loop = false;

    /**
     * @var bool
     */
    private $reverseAnimated = true;

    /**
     * @var array
     */
    private $ownerBindIds = [];

    /**
     * UIAnimation constructor.
     * @param mixed $duration
     * @param mixed $delay
     * @param string $when
     */
    public function __construct($duration = '0.5s', $delay = 0, string $when = 'render')
    {
        parent::__construct();

        $this->idStyle = 's' . str::uuid();

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
            $this->trigger(new Event('initialize', $this, $owner));

            switch ($this->when) {
                case 'render':
                    $this->ownerBindIds['render'] = $owner->bind('render', function () use ($owner) {
                        $this->animate($owner);
                    });

                    break;

                case 'click':
                    $clickCount = 0;
                    $this->ownerBindIds['click'] = $owner->bind('click', function () use ($owner, &$clickCount) {
                        if ($clickCount % 2 == 0) {
                            $this->animate($owner);
                        } else {
                            $this->reverseAnimate($owner);
                        }

                        $clickCount += 1;
                    });
                    break;

                case 'hover':
                    $this->ownerBindIds['mouseEnter'] = $owner->bind('mouseEnter', function () use ($owner) {
                        $this->animate($owner);
                    });
                    $this->ownerBindIds['mouseLeave'] = $owner->bind('mouseLeave', function () use ($owner) {
                        $this->reverseAnimate($owner);
                    });
                    break;
            }
        }
    }

    /**
     * @event removeFrom
     * @param Event $e
     */
    public function handleRemoveFrom(Event $e)
    {
        $owner = $e->context;

        if ($owner instanceof UINode) {
            foreach ($this->ownerBindIds as $event => $bindId) {
                $owner->off($event, $bindId);
            }

            $this->ownerBindIds = [];
            $this->trigger(new Event('finalize', $this, $owner));
        }
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

    /**
     * @return bool
     */
    protected function isLoop(): bool
    {
        return $this->loop;
    }

    /**
     * @param bool $loop
     */
    protected function setLoop(bool $loop)
    {
        $this->loop = $loop;
    }

    /**
     * @return bool
     */
    protected function isReverseAnimated(): bool
    {
        return $this->reverseAnimated;
    }

    /**
     * @param bool $reverseAnimated
     */
    protected function setReverseAnimated(bool $reverseAnimated)
    {
        $this->reverseAnimated = $reverseAnimated;
    }
}