<?php
namespace framework\web;
use php\lib\str;

/**
 * Class WebConsole
 * @package framework\web
 */
class WebConsole
{
    /**
     * @var UI
     */
    private $ui;

    /**
     * WebConsole constructor.
     * @param UI $ui
     */
    public function __construct(UI $ui)
    {
        $this->ui = $ui;
    }

    /**
     * @param string $type
     * @param string $message
     * @param array ...$args ....$args
     */
    protected function _log(string $type, string $message, ...$args)
    {
        foreach ($args as $i => $arg) {
            $message = str::replace($message, "\{$i\}", $arg);
        }

        $this->ui->sendMessage('system-console-log', [
            'kind' => $type,
            'message' => $message
        ]);
    }

    /**
     * Clear console.
     */
    public function clear() {
        $this->_log('clear', '');
    }

    /**
     * @param string $message
     * @param array ...$args
     */
    public function log(string $message, ...$args) {
        $this->_log('log', $message, ...$args);
    }

    /**
     * @param string $message
     * @param array ...$args
     */
    public function info(string $message, ...$args) {
        $this->_log('info', $message, ...$args);
    }

    /**
     * @param string $message
     * @param array ...$args
     */
    public function warn(string $message, ...$args) {
        $this->_log('warn', $message, ...$args);
    }

    /**
     * @param string $message
     * @param array ...$args
     */
    public function error(string $message, ...$args) {
        $this->_log('error', $message, ...$args);
    }

    /**
     * @param string $message
     * @param array ...$args
     */
    public function debug(string $message, ...$args) {
        $this->_log('debug', $message, ...$args);
    }

    /**
     * @param string $message
     * @param array ...$args
     */
    public function trace(string $message, ...$args) {
        $this->_log('trace', $message, ...$args);
    }
}