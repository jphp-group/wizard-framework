<?php
namespace framework\web\ui;

/**
 * Class UXHBox
 * @package framework\web\ui
 *
 * @property int $spacing
 */
class UXHBox extends UXContainer
{
    /**
     * @var int
     */
    private $spacing = 0;

    public function uiSchemaClassName(): string
    {
        return 'HBox';
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