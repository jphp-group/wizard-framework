<?php
namespace bundle\slider\ui;

use framework\web\ui\UINode;

/**
 * Class UISlider
 * @package bundle\slider\ui
 *
 * @property mixed $value
 * @property int $min
 * @property int $max
 * @property int $step
 * @property string $tooltipVisibility
 */
class UISlider extends UINode
{
    /**
     * @var mixed
     */
    private $value = 0;

    /**
     * @var int
     */
    private $min = 0;

    /**
     * @var int
     */
    private $max = 100;

    /**
     * @var int
     */
    private $step = 1;

    /**
     * @var string
     */
    private $tooltipVisibility = 'show';

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'bundle.Slider';
    }

    /**
     * @return mixed
     */
    protected function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    protected function getMin(): int
    {
        return $this->min;
    }

    /**
     * @param int $min
     */
    protected function setMin(int $min)
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    protected function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    protected function setMax(int $max)
    {
        $this->max = $max;
    }

    /**
     * @return int
     */
    protected function getStep(): int
    {
        return $this->step;
    }

    /**
     * @param int $step
     */
    protected function setStep(int $step)
    {
        $this->step = $step;
    }

    /**
     * @return string
     */
    protected function getTooltipVisibility(): string
    {
        return $this->tooltipVisibility;
    }

    /**
     * @param string $tooltipVisibility
     */
    protected function setTooltipVisibility(string $tooltipVisibility)
    {
        $this->tooltipVisibility = $tooltipVisibility;
    }

    public function provideUserInput(array $data)
    {
        parent::provideUserInput($data);

        $this->provideUserInputProperties(['value'], $data);
    }
}