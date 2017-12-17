<?php
namespace framework\web\ui;

/**
 * Class UXLabel
 * @package framework\web\ui
 */
class UILabel extends UILabeled
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Label';
    }
}