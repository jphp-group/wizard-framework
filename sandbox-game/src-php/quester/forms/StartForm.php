<?php
namespace quester\forms;

use framework\web\ui\UILabel;
use framework\web\UIForm;
use php\time\Timer;

/**
 * Class MenuForm
 * @package quester\forms
 *
 * @path /
 *
 * @property UILabel $label
 */
class StartForm extends UIForm
{
    protected function getFrmFormat()
    {
        return "yml";
    }

    /**
     * @event show
     */
    protected function doShow()
    {
        $this->label->fadeIn('2s', function () {
            $this->label->fadeOut('2s');

            Timer::after('2s', function () {
                $this->appUI->navigateTo('Menu');
            });
        });
    }
}