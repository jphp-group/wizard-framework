<?php
namespace framework\web;

use framework\core\Component;
use framework\core\ContainerComponent;
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
class Controller extends ContainerComponent
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

        $moduleFile = reflect::typeModule(reflect::typeOf($this))->getName();
        $ext = fs::ext($moduleFile);
        $moduleFile = str::sub($moduleFile, 0, str::length($moduleFile) - str::length($ext) - 1) . ".module";

        if (Stream::exists($moduleFile)) {
            $stream = Stream::of($moduleFile);
            $json = (new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS))->parse($stream);
            $stream->close();

            $props = (array) $json['props'];
            $components = (array) $json['components'];

            foreach ($components as $component) {
                $component = new $component['type'];
                $this->addComponent($component);
            }
        }
    }
}