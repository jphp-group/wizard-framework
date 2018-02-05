<?php
namespace framework\web;

use php\io\IOException;

/**
 * Class AbstractSocketSession
 * @package framework\web
 */
abstract class AbstractUISession
{
    /**
     * @param string $text
     * @param callable|null $callback
     * @throws IOException
     */
    abstract public function sendText(string $text, ?callable $callback = null): void;

    /**
     * @return bool
     */
    abstract public function isOpen(): bool;

    /**
     * @param int $status
     * @param string|null $reason
     */
    public function close(int $status = 1000, string $reason = null): void
    {
    }

    public function disconnect(): void
    {
    }
}