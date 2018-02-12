<?php
namespace framework\web;

use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use php\http\HttpResourceHandler;

class WebAssets extends Component
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
     * @event addTo
     * @param Event $event
     */
    protected function handleAddTo(Event $event)
    {
        if ($event->context instanceof WebApplication) {
            $this->app = $event->context;

            $handler = new HttpResourceHandler($this->directory);
            $handler->dirAllowed(false);

            $this->app->server()->get("$this->path/**", $handler);

            Logger::info("Add Web Assets: {0}/** --> {1}", $this->path, $this->directory);
        }
    }
}