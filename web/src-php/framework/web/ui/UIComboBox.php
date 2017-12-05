<?php
namespace framework\web\ui;

/**
 * Class UXComboBox
 * @package framework\web\ui
 */
class UIComboBox extends UISelectControl
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Combobox';
    }
}