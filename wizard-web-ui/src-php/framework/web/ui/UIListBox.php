<?php
namespace framework\web\ui;

/**
 * Class UIListBox
 * @package framework\web\ui
 */
class UIListBox extends UISelectControl
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Listbox';
    }
}