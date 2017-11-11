<?php
namespace framework\core;

use php\io\Stream;
use php\lib\str;

/**
 * Class Logger
 * @package framework\core
 */
class Logger
{
    protected static $writeHandlers = [];

    /**
     * @param callable $writeHandler
     * @param string $id
     */
    public static function addWriter(callable $writeHandler, string $id = 'general')
    {
        static::$writeHandlers[$id] = $writeHandler;
    }

    /**
     * @return callable
     */
    public static function stdoutWriter(): callable
    {
        $stdout = Stream::of("php://stdout");

        return function ($type, $message) use ($stdout) {
            $stdout->write("[$type] $message\n");
        };
    }

    /**
     * @param string $type
     * @param string $message
     * @param array ...$args
     * @return string
     */
    public static function format(string $type, string $message, ...$args): string
    {
        foreach ($args as $i => $arg) {
            $message = str::replace($message, "\{$i\}", $arg);
        }

        return $message;
    }

    /**
     * @param string $type
     * @param $message
     * @param array ...$args
     */
    public static function log(string $type, string $message, ...$args)
    {
        $message = static::format($type, $message, ...$args);

        foreach (static::$writeHandlers as $handler) {
            $handler($type, $message);
        }
    }

    /**
     * @param string $message
     * @param array ...$args
     */
    public static function info(string $message, ...$args)
    {
        static::log('INFO', $message, ...$args);
    }
}