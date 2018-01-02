<?php

use framework\web\UI;
use php\lang\Process;
use php\lib\str;
use php\time\Timer;

/**
 * @param callable $callback
 */
function uiLater(callable $callback)
{
    $ui = UI::currentRequired();

    Timer::after(1, function () use ($callback, $ui) {
        UI::setup($ui);
        call_user_func($callback);
    });
}

/**
 * @param callable $callback
 * @return mixed
 */
function uiLaterAndWait(callable $callback)
{
    UI::checkAvailable();
    return call_user_func($callback);
}

/**
 * Выполняет команду в рамках ОС и возвращает процесс.
 * @param string $command
 * @param bool $wait
 * @return Process
 */
function execute($command, $wait = false)
{
    $process = new Process(str::split($command, ' '));

    return $wait ? $process->startAndWait() : $process->start();
}

/**
 * @param string $message
 * @deprecated use alert().
 */
function message(string $message)
{
    alert($message);
}