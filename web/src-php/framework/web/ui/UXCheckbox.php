<?php
namespace framework\web\ui;

/**
 * Class UXCheckbox
 * @package framework\web\ui
 *
 * @property bool $selected
 */
class UXCheckbox extends UXLabeled
{
    /**
     * @var bool
     */
    private $selected = false;

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Checkbox';
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     */
    public function setSelected(bool $selected)
    {
        $this->selected = $selected;
    }

    public function provideUserInput(array $data)
    {
        if (isset($data['selected'])) {
            $this->selected = $data['selected'];
        }
    }
}