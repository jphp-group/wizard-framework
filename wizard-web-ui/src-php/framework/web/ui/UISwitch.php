<?php
namespace framework\web\ui;

/**
 * Class UISwitch
 * @package framework\web\ui
 *
 * @property string $kind
 */
class UISwitch extends UICheckbox
{
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
}
