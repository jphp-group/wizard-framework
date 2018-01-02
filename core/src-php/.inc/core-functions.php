<?php

use php\lang\Thread;
use php\time\Timer;

/**
 * --RU--
 * Пауза в выполнении кода в миллисекундах или во временном периоде.
 * 1 сек = 1000 млсек.
 *
 * for example '2h 30m 10s' or '2.5s' or '2000' or '1m 30s'
 *
 * @param int|string $period
 */
function wait($period)
{
    Thread::sleep(Timer::parsePeriod($period));
}


/**
 * --RU--
 * Ассинхронная пауза в выполнении кода с колбэком.
 *
 * @param int|string $period
 * @param callable $callback
 * @return Timer
 */
function waitAsync($period, callable $callback)
{
    return Timer::after($period, $callback);
}
