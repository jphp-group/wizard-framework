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