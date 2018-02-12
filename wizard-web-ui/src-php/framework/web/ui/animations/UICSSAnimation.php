<?php
namespace framework\web\ui\animations;

use framework\core\Event;
use framework\core\Logger;
use framework\web\ui\UINode;
use php\lib\arr;
use php\lib\str;
use php\time\Timer;

/**
 * @package framework\web\ui\animations
 *
 * @property array $frames
 */
class UICSSAnimation extends UIAnimationComponent
{
    /**
     * @var array
     */
    private $frames = [];

    /**
     * @var Timer
     */
    private $timer;

    /**
     * UIFadeAnimation constructor.
     * @param array $frames
     * @param string $duration
     * @param int $delay
     * @param string $when
     */
    public function __construct(array $frames = [], $duration = '0.5s', $delay = 0, $when = 'render')
    {
        parent::__construct($duration, $delay, $when);

        $this->frames = $frames;
    }

    protected function makeCssAnimation($name, array $frames, bool $reverse = false)
    {
        if ($reverse) $frames = arr::reverse($frames);

        $size = sizeof($frames) - 1;

        $result = "@keyframes $name {";

        foreach ($frames as $i => $frame) {
            $percent = ($i / $size) * 100;

            $result .= "{$percent}% {";

            $result .= flow($frame)->reduce(function ($result, $value, $prop) {
                return "$result $prop: $value;";
            });

            $result .= " }\n";
        }

        $result .= "}";

        return $result;
    }

    /**
     * @event initialize
     */
    public function handleInitialize()
    {
        $node = $this->owner;

        if ($node instanceof UINode) {
            $frame = arr::first($this->frames);

            if (is_array($frame)) {
                $node->css($frame);
            }
        }
    }

    /**
     * @event finalize
     */
    public function handleFinalize()
    {
        $node = $this->owner;

        if ($node instanceof UINode) {
            $node->getConnectedUI()->destroyCssStyle("anim" . $this->idStyle);
        }
    }

    /**
     * @event animate
     */
    public function handleAnimate()
    {
        $node = $this->owner;

        if ($this->timer) $this->timer->cancel();

        if ($node instanceof UINode) {
            $duration = Timer::parsePeriod($this->duration);

            $node->getConnectedUI()->createCssStyle(
                $this->makeCssAnimation("CssAnimation_{$this->idStyle}", $this->frames),
                "anim" . $this->idStyle
            );

            $apply = function () use ($duration, $node) {
                $css = arr::last($this->frames);
                $css['animation'] = "CssAnimation_{$this->idStyle} {$duration}ms linear";

                if ($this->isLoop()) {
                    $css['animation'] .= " infinite";
                }

                $node->css($css);
            };

            if ($this->delay > 0) {
                $this->timer = Timer::after($this->delay, function () use ($apply) {
                    $this->timer = null;
                    $apply();
                });
            } else {
                $apply();
            }
        }
    }

    /**
     * @event reverseAnimate
     * @param Event $e
     */
    public function handleReverseAnimate(Event $e)
    {
        /** @var UINode $node */
        $node = $this->owner;

        if ($this->timer) $this->timer->cancel();

        if ($node instanceof UINode) {
            $duration = Timer::parsePeriod($this->duration);

            $this->owner->getConnectedUI()->createCssStyle(
                $this->makeCssAnimation("CssAnimation_{$this->idStyle}_reverse", $this->frames, true),
                "anim" . $this->idStyle
            );

            $apply = function () use ($duration, $node) {
                $css = arr::first($this->frames);

                if ($this->isReverseAnimated()) {
                    $css['animation'] = "CssAnimation_{$this->idStyle}_reverse {$duration}ms linear";
                } else {
                    $css['animation'] = null;
                }

                $node->css($css);
            };

            if ($this->delay) {
                $this->timer = Timer::after($this->delay, function () use ($apply) {
                    $this->timer = null;
                    $apply();
                });
            } else {
                $apply();
            }
        }
    }

    /**
     * @return array
     */
    protected function getFrames(): array
    {
        return $this->frames;
    }

    /**
     * @param array $frames
     */
    protected function setFrames(array $frames)
    {
        $this->frames = $frames;
    }
}