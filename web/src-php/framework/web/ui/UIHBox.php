<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 *
 * @property int $spacing
 */
class UIHBox extends UIContainer
{
    /**
     * @var int
     */
    private $spacing = 0;

    /**
     * UIHBox constructor.
     * @param array $children
     * @param int $spacing
     */
    public function __construct(array $children = [], int $spacing = 0)
    {
        parent::__construct($children);

        $this->spacing = $spacing;
    }


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