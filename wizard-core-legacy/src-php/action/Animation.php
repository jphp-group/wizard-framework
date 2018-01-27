<?php
namespace action;

use framework\web\ui\UINode;
use php\gui\framework\Instances;

/**
 * Class Animation
 * @package action
 *
 * @packages framework
 */
class Animation
{
    /**
     * Fade animation
     * --RU--
     * Анимация затухания
     *
     * @param UINode|Instances $object
     * @param $duration
     * @param $value
     * @param callable|null $callback
     */
    static function fadeTo($object, $duration, $value, callable $callback = null): void
    {
        if ($object instanceof Instances) {
            $cnt = sizeof($object);

            $done = function () use (&$cnt, $callback) {
                $cnt--;

                if ($cnt <= 0) {
                    $callback();
                }
            };

            $object->flow()->map(function () use ($object, $duration, $value, $done) {
                Animation::fadeTo($object, $duration, $value, $done);
            });

            return;
        }

        if ($object instanceof UINode) {
            $object->fadeTo($duration, $value, $callback);
        }
    }

    static function fadeIn($object, $duration, callable $callback = null)
    {
        self::fadeTo($object, $duration, 1.0, $callback);
    }

    static function fadeOut($object, $duration, callable $callback = null)
    {
        self::fadeTo($object, $duration, 0.0, $callback);
    }
}