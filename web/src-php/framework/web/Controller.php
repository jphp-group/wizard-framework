<?php
namespace framework\web;

use framework\core\Annotations;
use framework\core\Component;
use framework\core\ComponentLoader;
use framework\core\Module;
use php\format\JsonProcessor;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;
use php\io\Stream;
use php\lang\Thread;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;

/**
 * Class Controller
 * @package framework\web
 */
class Controller extends Module
{
    /**
     * @var HttpServerRequest
     */
    protected $request;

    /**
     * @var HttpServerResponse
     */
    protected $response;

    /**
     * @param HttpServerRequest $request
     * @param HttpServerResponse $response
     */
    public function initialize(HttpServerRequest $request, HttpServerResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return Annotations::getOfClass('path', new \ReflectionClass($this), '');
    }
}