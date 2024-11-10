<?php
/*
* File: translation.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 23:08
* Updated: -
*
* Description:
*  -
*/

if(!function_exists('translate')) {
    function translate(string $key, array $params = []): string {
        return \Webklex\CalMag\Translator::translate($key, $params);
    }
}

if(!function_exists('__')) {
    function __(string $key, array $params = []): string {
        return translate($key, $params);
    }
}