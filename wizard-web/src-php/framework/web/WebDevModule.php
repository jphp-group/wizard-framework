<?php

namespace framework\web;

use framework\core\Component;
use framework\core\Event;
use php\time\Timer;

/**
 * Class WebDevModule
 * @package framework\web
 */
class WebDevModule extends Component
{
    /**
     * WebDevModule constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @event addTo
     * @param Event $event
     */
    protected function handleAddTo(Event $event)
    {
        if ($event->context instanceof WebApplication) {
            $app = $event->context;

            $app->server()->get('/@dev/shutdown', function () use ($app) {
                Timer::after('0.2s', function () use ($app) {
                    $app->shutdown();
                });

                return "OK";
            });
        }
    }
}