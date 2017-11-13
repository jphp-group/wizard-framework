<?php
namespace components;

use framework\core\Component;
use framework\web\Controller;

/**
 * Class HelloWorldComponent
 * @package components
 * @singleton
 */
class HelloWorldComponent extends Component
{
    /**
     * @var string
     */
    public $name = 'World';
}