<?php

use php\lib\str;


/**
 * Class Dialog
 *
 * @packages framework
 */
class Dialog
{
    /**
     * @param string $text
     * @param string $type
     */
    static function show($text, $type = 'INFORMATION')
    {
        alert($text, ['type' => str::lower($type)]);
    }

    /**
     * @param $text
     * @param string $type
     */
    static function message($text, $type = 'INFORMATION')
    {
        static::show($text, $type);
    }

    /**
     * @param $text
     * @param string $type
     */
    static function alert($text, $type = 'INFORMATION')
    {
        static::show($text, $type);
    }

    /**
     * @param $text
     */
    static function error($text)
    {
        static::show($text, 'ERROR');
    }

    /**
     * @param $text
     */
    static function warning($text)
    {
        static::show($text, 'WARNING');
    }

    /**
     * @param string $text
     * @throws Exception
     */
    static function confirm(string $text)
    {
        throw new Exception("Dialog::confirm is not available, use UIAlert dialog with type confirm.");
    }

    /**
     * @param string $text
     * @param string $default
     * @throws Exception
     */
    static function input(string $text, string $default = '')
    {
        throw new Exception("Dialog::input is not available.");
    }
}