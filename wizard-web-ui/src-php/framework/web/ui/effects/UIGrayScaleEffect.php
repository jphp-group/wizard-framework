<?php
namespace framework\web\ui\effects;

/**
 * Class UIGrayScaleEffect
 * @package framework\web\ui\effects
 *
 * @property int $level
 */
class UIGrayScaleEffect extends UIFilterEffectComponent
{
    /**
     * @var float
     */
    private $level = 1.0;

    /**
     * UIGrayScaleEffect constructor.
     * @param float $level
     */
    public function __construct(float $level = 1.0)
    {
        parent::__construct();

        $this->level = $level;
    }

    /**
     * @return float
     */
    protected function getLevel(): float
    {
        return $this->level;
    }

    /**
     * @param float $level
     */
    protected function setLevel(float $level)
    {
        $this->level = $level;
    }

    protected function makeCssFilter(): string
    {
        return "grayscale($this->level)";
    }
}