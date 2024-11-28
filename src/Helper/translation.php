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

if(!function_exists('pretty_number')) {
    function pretty_number(mixed $value, string $unit, int $decimals = 2, ?string $decimal_separator = '.', ?string $thousands_separator = ''): string {
        switch ($unit) {
            case "mg":
                if($value >= 1000){
                    return pretty_number($value / 1000, "g");
                }
                break;
            case "g":
                if($value >= 1000){
                    return pretty_number($value / 1000, "kg");
                }elseif ($value < 1){
                    return pretty_number($value * 1000, "mg");
                }
                break;
            case "kg":
                if($value < 1){
                    return pretty_number($value * 1000, "g");
                }
                break;
            case "ml":
                if($value >= 1000){
                    return pretty_number($value / 1000, "l");
                }
                break;
            case "l":
                if ($value < 1){
                    return pretty_number($value * 1000, "ml");
                }
                break;
            default:
                break;
        }
        return number_format($value, $decimals, $decimal_separator, $thousands_separator)." ".$unit;
    }
}