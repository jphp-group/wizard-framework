<?php

use framework\web\UI;

/**
 * @param string $url
 */
function browse(string $url)
{
    UI::currentRequired()->executeScript("window.location = '$url';");
}

/**
 * @param string $message
 * @param array $options
 */
function alert(string $message, array $options = []) {
    UI::currentRequired()->alert($message, $options);
}

/**
 * @param mixed $data
 */
function pre($data) {
    alert(print_r($data, true), ['type' => '', 'pre' => true, 'title' => '[DEBUG]: pre()']);
}

/**
 * @param $arg
 */
function dump($arg) {
    ob_start();
    var_dump($arg);
    $msg = ob_get_contents();
    ob_end_clean();

    alert($msg, ['type' => '', 'pre' => true, 'title' => '[DEBUG]: dump()']);
}