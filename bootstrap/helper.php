<?php

if (!function_exists('pp')) {
    function pp()
    {
        $args = func_get_args();

        if (!empty($args)) {
            echo '<pre>';
            foreach ($args as $arg) {
                print_r($arg);
            }
            echo '</pre>';
        }
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();

        if (!empty($args)) {
            echo '<pre>';
            foreach ($args as $arg) {
                print_r($arg);
            }
            echo '</pre>';
        }
        exit;
    }
}
