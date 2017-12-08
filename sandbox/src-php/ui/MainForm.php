<?php
namespace ui;

use framework\web\ui\UIButton;
use framework\web\ui\UIHBox;
use framework\web\ui\UIImageView;
use framework\web\UIForm;
use php\lib\str;
use php\time\Timer;

/**
 * Class MainForm
 * @package ui
 *
 * @path /
 *
 * @property UIHBox $pane
 * @property UIButton $button
 *
 */
class MainForm extends UIForm
{
    /**
     * @event button.click
     */
    public function doButtonClick()
    {
        alert('5555');
    }
}