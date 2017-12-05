<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 */
class UITextArea extends UITextInputControl
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'TextArea';
    }
}