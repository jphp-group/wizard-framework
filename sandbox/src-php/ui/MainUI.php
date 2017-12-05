<?php
namespace ui;

use framework\web\AppUI;

/**
 * Class MainUI
 * @package ui
 *
 * @path /app
 */
class MainUI extends AppUI
{
    public function __construct()
    {
        parent::__construct();

        $this->registerForm('MainForm', new MainForm());
        $this->registerForm('OtherForm', new OtherForm());

        $this->registerNotFoundForm('NotFound', new NotFoundForm());
    }
}