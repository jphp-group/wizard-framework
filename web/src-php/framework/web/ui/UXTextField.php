<?php
namespace framework\web\ui;

/**
 * Class UXTextField
 * @package framework\web\ui
 */
class UXTextField extends UXTextInputControl
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'TextField';
    }
}