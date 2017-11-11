<?php
namespace components;

use framework\core\Component;
use framework\web\Controller;

/**
 * Class HelloWorldComponent
 * @package components
 */
class HelloWorldComponent extends Component
{
    /**
     * @var string
     */
    public $name = 'World';

    /**
     * @event register
     * @param Controller $controller
     */
    public function onRegister(Controller $controller)
    {
    }

    /**
     * @GET
     * @path /hello-world
     */
    public function helloWorld()
    {
        return "Hello, $this->name";
    }
}