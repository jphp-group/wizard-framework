<?php
namespace ui;

use framework\web\ui\UIHBox;
use framework\web\ui\UIImageView;
use framework\web\UIForm;

/**
 * Class MainForm
 * @package ui
 *
 * @path /
 *
 * @property UIHBox $pics
 */
class MainForm extends UIForm
{
    /**
     * @event button.click
     */
    public function doButtonClick()
    {
        //$this->appUI->navigateTo('OtherForm');

        $dialog = new InputTextDialog(function ($text) {
            $image = new UIImageView($text);
            $this->pics->add($image);
        });

        $dialog->show();
    }
}