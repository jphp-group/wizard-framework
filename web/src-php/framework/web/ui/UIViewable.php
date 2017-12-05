<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 */
interface UIViewable
{
    /**
     * @return array
     */
    function uiSchema(): array;
}