<?php
namespace ui;

use framework\web\UIForm;

/**
 * Class MainForm
 * @package ui
 *
 * @path /
 */
class OtherForm extends UIForm
{
    protected function getFrmFormat()
    {
        return 'yml';
    }
}