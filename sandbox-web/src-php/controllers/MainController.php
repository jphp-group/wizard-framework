<?php
namespace controllers;

use components\HelloWorldComponent;
use framework\core\Logger;
use framework\web\Controller;
use modules\AppModule;

/**
 * Class MainController
 * @package controllers
 *
 * @path /app
 *
 * @property HelloWorldComponent $greeting
 * @property AppModule $appModule
 */
class MainController extends Controller
{
    /**
     * @event beforeRequest
     */
    public function beforeRequest()
    {
        Logger::info("{0} -> {1} [{2}]", $this->request->method(), $this->request->path(), $this->request->sessionId());
    }

    /**
     * @GET
     * @path /
     */
    public function index()
    {
        return 'OK';
    }

    /**
     * @GET
     * @path /greeting
     */
    public function greeting()
    {
        if ($name = $this->request->query('name')) {
            $this->appModule->name = $name;
        }

        return 'Hello, ' . $this->appModule->name . '!';
    }
}