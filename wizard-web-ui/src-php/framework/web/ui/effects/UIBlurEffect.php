<?php
namespace framework\web\ui\effects;

/**
 * @property float $length
 */
class UIBlurEffect extends UIFilterEffectComponent
{
    /**
     * @var float
     */
    private $length = 5;

    public function __construct(float $length = 5.0)
    {
        parent::__construct();

        $this->length = $length;
    }

    protected function getLength(): float
    {
        return $this->length;
    }

    protected function setLength(float $length)
    {
        $this->length = $length;
    }

    protected function makeCssFilter(): string
    {
        return "blur({$this->length}px)";
    }
}