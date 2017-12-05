<?php
namespace ui;

use framework\core\Event;
use framework\core\Logger;
use framework\web\UIForm;
use php\lib\str;
use php\time\Timer;

/**
 * Class OtherForm
 * @package ui
 */
class OtherForm extends UIForm
{
    /**
     * @event button.click
     */
    public function doButtonClick()
    {
        $this->appUI->navigateTo('MainForm');
    }

    /**
     * @event leave
     */
    public function doLeave()
    {
        $this->label->text = "Super Text";
    }
}