<?php
namespace framework\web\ui;

/**
 * Class UXLabel
 * @package framework\web\ui
 */
class UXLabel extends UXLabeled
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Label';
    }
}