<?php
namespace framework\web\ui;

/**
 * Class UIToggleButton
 * @package framework\web\ui
 *
 * @property bool $selected
 */
class UIToggleButton extends UIButton
{
    /**
     * @var bool
     */
    private $selected = false;

    /**
     * @return bool
     */
    protected function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     */
    protected function setSelected(bool $selected)
    {
        $this->selected = $selected;
    }

    public function provideUserInput(array $data)
    {
        parent::provideUserInput($data);

        if (isset($data['selected'])) {
            $this->selected = (bool) $data['selected'];
        }
    }

    public function synchronizeUserInput(array $data)
    {
        parent::synchronizeUserInput($data);

        if (isset($data['selected'])) {
            $this->changeRemoteProperty('selected', (bool) $data['selected']);
        }
    }
}