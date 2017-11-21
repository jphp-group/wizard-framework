<?php
namespace framework\web\ui;

/**
 * Class UXVBox
 * @package framework\web\ui
 *
 * @property int $spacing
 */
class UXVBox extends UXContainer
{
    /**
     * @var int
     */
    private $spacing = 0;

    public function uiSchemaClassName(): string
    {
        return 'VBox';
    }

    /**
     * @return int
     */
    public function getSpacing(): int
    {
        return $this->spacing;
    }

    /**
     * @param int $spacing
     */
    public function setSpacing(int $spacing)
    {
        $this->spacing = $spacing;
    }
}