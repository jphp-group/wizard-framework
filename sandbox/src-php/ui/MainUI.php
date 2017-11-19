<?php
namespace ui;

use framework\web\UI;
use framework\web\ui\UXButton;
use framework\web\ui\UXNode;

/**
 * Class MainUI
 * @package ui
 *
 * @path /main
 */
class MainUI extends UI
{
    /**
     * @return UXNode
     */
    protected function makeView(): UXNode
    {
        return new UXButton('Hello, World!');
    }
}