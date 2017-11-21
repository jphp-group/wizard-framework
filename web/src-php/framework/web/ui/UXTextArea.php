<?php
namespace framework\web\ui;

/**
 * Class UXTextArea
 * @package framework\web\ui
 */
class UXTextArea extends UXTextInputControl
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'TextArea';
    }
}