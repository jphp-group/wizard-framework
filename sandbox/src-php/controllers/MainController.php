<?php
namespace controllers;

use framework\core\Logger;
use framework\web\Controller;

/**
 * Class MainController
 * @package controllers
 *
 * @path /app
 */
class MainController extends Controller
{
    /**
     * @event beforeRequest
     */
    public function beforeRequest()
    {
        Logger::info("{0} -> {1} [{2}]", $this->request->method(), $this->request->path(), $this->request->header('user-agent'));
    }

    /**
     * @GET
     * @path /
     */
    public function index()
    {
        return 'OK';
    }
}