<?php
namespace ui;

use framework\web\UIForm;

/**
 * Class MainForm
 * @package ui
 *
 * @path /
 */
class MainForm extends UIForm
{
    protected function getFrmFormat()
    {
        return 'yml';
    }

    /**
     * @event button.click
     */
    public function doClick()
    {
        alert("Yes, It's work!");
    }
}