<?php
namespace ui;

use framework\core\Logger;
use framework\web\UI;
use framework\web\ui\UXAnchorPane;
use framework\web\ui\UXButton;
use framework\web\ui\UXContainer;
use framework\web\ui\UXHBox;
use framework\web\ui\UXLabel;
use framework\web\ui\UXNode;
use framework\web\ui\UXVBox;
use php\time\Timer;

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
        $box = new UXVBox();
        $box->spacing = 10;
        $box->height = '100%';
        $box->align = ['center', 'center'];

        $button = new UXButton('Hello, World!');
        $button->on('click', function () use ($button, $box) {
            $this->alert('Привет Мир!');
            $button->text = 'Done!';
            $button->hide();

            $box->on('click', function () use ($button) {
                $button->kind = 'primary';
            });

            Timer::after('3s', function () use ($button) {
                $button->show();
            });
        });

        $box->add($button);
        $box->add(new UXLabel('Description'));

        $pane = new UXAnchorPane();
        $pane->width = 300;
        $pane->height = 200;
        $pane->add(new UXLabel('Point'));

        $box->add($pane);

        return $box;
    }
}