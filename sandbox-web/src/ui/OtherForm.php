<?php
namespace ui;

use framework\web\UIForm;

/**
 * Class OtherForm
 * @package ui
 *
 * @path /other
 */
class OtherForm extends UIForm
{
    protected function getFrmFormat()
    {
        return 'yml';
    }
}