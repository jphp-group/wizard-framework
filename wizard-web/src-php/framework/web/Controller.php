<?php
namespace framework\web;

use framework\core\Annotations;
use framework\core\Component;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;

/**
 * Class Controller
 * @package framework\web
 */
class Controller extends Component
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