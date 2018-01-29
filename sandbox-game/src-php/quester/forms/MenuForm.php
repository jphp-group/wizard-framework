<?php
namespace quester\forms;

use framework\web\UIForm;

/**
 * Class MenuForm
 * @package quester\forms
 *
 * @path /menu
 */
class MenuForm extends UIForm
{
    protected function getFrmFormat()
    {
        return 'yml';
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->menu->fadeIn(1000);
    }

    /**
     * @event exitGameBtn.click
     */
    public function doExit()
    {
        $this->appUI->navigateTo('Start');
    }

    /**
     * @event startGameBtn.click
     */
    public function doStart()
    {
        $this->appUI->navigateTo(new StoryForm());
    }
}