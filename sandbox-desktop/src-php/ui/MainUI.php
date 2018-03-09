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

        $this->registerNotFoundForm('Main', new MainForm());
    }
}