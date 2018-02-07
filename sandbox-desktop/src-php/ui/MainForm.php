<?php
namespace ui;

use framework\web\UIForm;

/**
 * Class MainForm
 * @package ui
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
        $form = new OtherForm();
        $form->show();
    }
}