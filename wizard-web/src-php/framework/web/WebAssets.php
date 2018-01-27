<?php
namespace framework\web;

use framework\core\Event;
use framework\core\Logger;
use framework\core\Module;
use php\http\HttpResourceHandler;

class WebAssets extends Module
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $path;

    /**
     * @var WebApplication
     */
    private $app;

    public function __construct(string $path, string $directory)
    {
        parent::__construct();
        $this->path = $path;
        $this->directory = $directory;
    }

    /**
     * @event inject
     * @param Event $event
     */
    protected function onInject(Event $event)
    {
        if ($event->context instanceof WebApplication) {
            $this->app = $event->context;

            $handler = new HttpResourceHandler($this->directory);

            $this->app->server()->get("$this->path/**", $handler);

            Logger::info("Add Web Assets: {0}/** --> {1}", $this->path, $this->directory);
        }
    }
}