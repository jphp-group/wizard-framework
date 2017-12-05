<?php

use framework\web\UI;

/**
 * @param string $message
 */
function alert(string $message) {
    UI::currentRequired()->alert($message);
}

/**
 * @param mixed $data
 */
function pre($data) {
    alert(print_r($data, true));
}

/**
 * @param $arg
 */
function dump($arg) {
    ob_start();
    var_dump($arg);
    $msg = ob_get_contents();
    ob_end_clean();

    alert($msg);
}