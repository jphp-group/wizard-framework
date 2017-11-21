<?php
namespace ui;

use framework\core\Event;
use framework\core\Logger;
use framework\web\UI;
use framework\web\ui\UXAnchorPane;
use framework\web\ui\UXButton;
use framework\web\ui\UXCheckbox;
use framework\web\ui\UXContainer;
use framework\web\ui\UXHBox;
use framework\web\ui\UXLabel;
use framework\web\ui\UXNode;
use framework\web\ui\UXTextField;
use framework\web\ui\UXVBox;
use php\lib\str;
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
        $field = new UXTextField();
        $field->width = 200;
        $label = new UXLabel();
        $checkbox = new UXCheckbox('Checkbox');


        $button = new UXButton('Hello, World!');
        $button->on('click', function (Event $e) use ($button, $box, $checkbox) {
            var_dump($e->data);
        });

        $field->on('keyUp', function () use ($label, $field) {
            $label->text = $field->text;
        });

        $box->add($button);
        $box->add($field);
        $box->add($label);
        $box->add($checkbox);

        return $box;
    }
}