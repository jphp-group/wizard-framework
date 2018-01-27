<?php
namespace framework\web;

use framework\core\Module;

/**
 * Class UIModule
 * @package framework\web
 */
abstract class UIModule extends Module
{
    /**
     * @return array
     */
    public function getResources(): array
    {
        return [];
    }

    /**
     * @return array
     */
    abstract public function getRequiredResources(): array;
}