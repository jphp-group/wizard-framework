<?php

namespace framework\web;

use framework\core\Event;
use framework\core\Module;
use php\time\Timer;

/**
 * Class WebDevModule
 * @package framework\web
 */
class WebDevModule extends Module
{
    /**
     * WebDevModule constructor.
     * @param HotDeployer $deployer
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @event inject
     * @param Event $event
     */
    protected function onInject(Event $event)
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