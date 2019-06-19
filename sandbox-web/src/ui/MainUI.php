<?php
namespace ui;

use framework\web\AppUI;

/**
 * Class MainUI
 * @package ui
 *
 * @path /
 */
class MainUI extends AppUI
{
    public function __construct()
    {
        parent::__construct();

        $this->registerForm('MainForm', new MainForm());
        $this->registerForm('OtherForm', new OtherForm());
        $this->registerForm('CodeForm', new CodeForm());

        $this->registerNotFoundForm('NotFound', new NotFoundForm());
    }
}
