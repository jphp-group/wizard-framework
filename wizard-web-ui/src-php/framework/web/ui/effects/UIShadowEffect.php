<?php
namespace framework\web\ui\effects;

use framework\web\ui\UILabeled;
use php\lib\str;

/**
 * Class UIShadowEffect
 * @package framework\web\ui\effects
 */
class UIShadowEffect extends UIEffectComponent
{
    /**
     * @var float
     */
    private $radius = 10.0;

    /**
     * @var string
     */
    private $color = 'gray';

    /**
     * @var array
     */
    private $offset = [0, 0];

    /**
     * @var bool
     */
    private $inner = false;

    /**
     * @var string
     */
    private $currentStyle = '';

    /**
     * UIShadowEffect constructor.
     * @param float $radius
     * @param string $color
     * @param array $offset
     * @param bool $inner
     */
    public function __construct($radius = 10.0, string $color = 'gray', array $offset = [0, 0], bool $inner = false)
    {
        parent::__construct();

        $this->radius = $radius;
        $this->color = $color;
        $this->offset = $offset;
        $this->inner = $inner;
    }

    protected function getCssProperty(): string
    {
        return 'box-shadow';
    }

    /**
     * @event apply
     */
    protected function handleApply()
    {
        $propertyValue = $this->owner->css($this->getCssProperty());

        if ($this->currentStyle) {
            $propertyValue = str::replace($propertyValue, $this->currentStyle, '');
        }

        $propertyValue = str::split($propertyValue, ',');
        $propertyValue = flow($propertyValue)->find(function ($value) { return (bool) trim($value); })->toArray();

        [$x, $y] = $this->offset;

        $value = "{$x}px {$y}px {$this->radius}px $this->color";

        if ($this->inner) {
            $value = "inset $value";
        }

        $this->currentStyle = $value;
        $propertyValue[] = $value;

        $this->owner->css([
            $this->getCssProperty() => str::join($propertyValue, ',')
        ]);
    }

    /**
     * @event reset
     */
    protected function handleReset()
    {
        $propertyValue = $this->owner->css($this->getCssProperty());

        if ($this->currentStyle) {
            $propertyValue = str::replace($propertyValue, $this->currentStyle, '');
        }

        $propertyValue = str::split($propertyValue, ',');
        $propertyValue = flow($propertyValue)->find(function ($value) { return (bool) trim($value); })->toArray();

        $this->owner->css([
            $this->getCssProperty() => str::join($propertyValue, ',')
        ]);
    }

    /**
     * @event finalize
     */
    protected function handleFinalize()
    {
        $this->handleReset();
    }

    /**
     * @return float
     */
    protected function getRadius(): float
    {
        return $this->radius;
    }

    /**
     * @param float $radius
     */
    protected function setRadius(float $radius)
    {
        $this->radius = $radius;
    }

    /**
     * @return string
     */
    protected function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    protected function setColor(string $color)
    {
        $this->color = $color;
    }

    /**
     * @return array
     */
    protected function getOffset(): array
    {
        return $this->offset;
    }

    /**
     * @param array $offset
     */
    protected function setOffset(array $offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return bool
     */
    protected function isInner(): bool
    {
        return $this->inner;
    }

    /**
     * @param bool $inner
     */
    protected function setInner(bool $inner)
    {
        $this->inner = $inner;
    }
}