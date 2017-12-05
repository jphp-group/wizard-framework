<?php
namespace ui;

use framework\web\ui\UITextField;
use framework\web\UIForm;

/**
 * Class InputTextDialog
 * @package ui
 *
 * @property UITextField $input
 */
class InputTextDialog extends UIForm
{
    private $okHandler;

    /**
     * InputTextDialog constructor.
     * @param callable $okHandler
     */
    public function __construct(callable $okHandler)
    {
        parent::__construct();

        $this->okHandler = $okHandler;
    }

    /**
     * @event done.click
     */
    public function doOkClick()
    {
        $handler = $this->okHandler;
        $handler($this->input->text);

        $this->hide();
    }
}