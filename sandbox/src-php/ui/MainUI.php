<?php
namespace ui;

use framework\core\Logger;
use framework\web\UI;
use framework\web\ui\UXButton;
use framework\web\ui\UXContainer;
use framework\web\ui\UXHBox;
use framework\web\ui\UXNode;

/**
 * Class MainUI
 * @package ui
 *
 * @path /main
 */
class MainUI extends UI
{
    /**
     * @return UXNode
     */
    protected function makeView(): UXNode
    {
        $button = new UXButton('Hello, World!');
        $button->on('click', function () use ($button) {
            $this->alert('Привет Мир!');
            $button->text = 'Done!';
        });

        $box = new UXHBox();
        $box->height = '100%';
        $box->align = ['center', 'center'];
        $box->add($button);

        return $box;
    }
}