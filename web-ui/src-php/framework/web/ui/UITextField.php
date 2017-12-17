<?php
namespace framework\web\ui;

/**
 * Class UXTextField
 * @package framework\web\ui
 */
class UITextField extends UITextInputControl
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'TextField';
    }
}