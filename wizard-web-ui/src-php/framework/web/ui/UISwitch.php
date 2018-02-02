<?php
namespace framework\web\ui;

/**
 * Class UISwitch
 * @package framework\web\ui
 *
 * @property string $kind
 * @property int $iconSize
 * @property int $iconGap
 * @property string $iconDisplay
 */
class UISwitch extends UICheckbox
{
    /**
     * @var int
     */
    private $iconSize = 16;

    /**
     * @var int
     */
    private $iconGap = 8;

    /**
     * left or right
     * @var string
     */
    private $iconDisplay = 'left';

    /**
     * @var string
     */
    private $kind = 'default';

    public function uiSchemaClassName(): string
    {
        return 'Switch';
    }

    /**
     * @return string
     */
    protected function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     */
    protected function setKind(string $kind)
    {
        $this->kind = $kind;
    }

    /**
     * @return int
     */
    protected function getIconSize(): int
    {
        return $this->iconSize;
    }

    /**
     * @param int $iconSize
     */
    protected function setIconSize(int $iconSize)
    {
        $this->iconSize = $iconSize;
    }

    /**
     * @return int
     */
    protected function getIconGap(): int
    {
        return $this->iconGap;
    }

    /**
     * @param int $iconGap
     */
    protected function setIconGap(int $iconGap)
    {
        $this->iconGap = $iconGap;
    }

    /**
     * @return string
     */
    protected function getIconDisplay(): string
    {
        return $this->iconDisplay;
    }

    /**
     * @param string $iconDisplay
     */
    protected function setIconDisplay(string $iconDisplay)
    {
        $this->iconDisplay = $iconDisplay;
    }
}
