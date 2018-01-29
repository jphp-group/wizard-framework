<?php
namespace quester;

use framework\web\AppUI;
use quester\forms\MenuForm;
use quester\forms\StartForm;
use quester\forms\StoryForm;

/**
 * Class GameUI
 * @package quester
 *
 * @path /game
 */
class GameUI extends AppUI
{
    public function __construct()
    {
        parent::__construct();

        $this->registerForm('Start', new StartForm());
        $this->registerForm('Menu', new MenuForm());
        //$this->registerForm('Story', new StoryForm());

        /*$this->registerForm('MainForm', new MainForm());
        $this->registerForm('OtherForm', new OtherForm());

        $this->registerNotFoundForm('NotFound', new NotFoundForm());*/
    }
}