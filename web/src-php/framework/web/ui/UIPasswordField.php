<?php
namespace framework\web\ui;

/**
 * Class UIPasswordField
 * @package framework\web\ui
 */
class UIPasswordField extends UITextInputControl
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'PasswordField';
    }
}