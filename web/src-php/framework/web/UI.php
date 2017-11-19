<?php
namespace framework\web;

use framework\core\Application;
use framework\core\Component;
use framework\web\ui\UXNode;
use php\format\JsonProcessor;
use php\http\HttpServerRequest;
use php\http\HttpServerResponse;
use php\io\Stream;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;

/**
 * Class UI
 * @package framework\web
 *
 * @scope session
 */
abstract class UI extends Component
{
    private $view;

    /**
     * UI constructor.
     */
    public function __construct()
    {
        $this->view = $this->makeView();
    }

    /**
     * @return UXNode
     */
    abstract protected function makeView(): UXNode;

    /**
     * @return UXNode
     */
    public function getView(): UXNode
    {
        return $this->view;
    }

    /**
     * @return array
     */
    final public function getUISchema(): array
    {
        return $this->view->uiSchema();
    }

    /**
     * @param HttpServerRequest $request
     * @param HttpServerResponse $response
     * @param string $path
     */
    final public function show(HttpServerRequest $request, HttpServerResponse $response, string $path)
    {
        $moduleFile = reflect::typeModule(__CLASS__)->getName();
        $ext = fs::ext($moduleFile);
        $moduleFile = str::sub($moduleFile, 0, str::length($moduleFile) - str::length($ext) - 1) . ".html";

        $body = fs::get($moduleFile);

        $body = str::replace($body, '{{dnextCSSUrl}}', '/dnext/engine-' . Application::current()->getStamp() . '.min.css');
        $body = str::replace($body, '{{dnextJSUrl}}', '/dnext/engine-' . Application::current()->getStamp() . '.js');

        $body = str::replace($body, '{{uiSchemaUrl}}', "$path/@ui-schema");

        $response->contentType('text/html');
        $response->body($body);
    }
}