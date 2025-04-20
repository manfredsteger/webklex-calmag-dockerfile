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
    /**
     * Get a string translation by key with optional parameter replacement
     * 
     * @param string $key Translation key
     * @param array $params Parameters to replace in the translation
     * @return string Translated text
     */
    function translate(string $key, array $params = []): string {
        return \Webklex\CalMag\Translator::translate($key, $params);
    }
}

if(!function_exists('__')) {
    /**
     * Shorthand function to get a string translation
     * 
     * @param string $key Translation key
     * @param array $params Parameters to replace in the translation
     * @return string|array Translated text or array of translations
     */
    function __(string $key, array $params = [], bool $is_array = false): string|array {
        if($is_array){
            return trans_array($key, $params);
        }
        return translate($key, $params);
    }
}

if(!function_exists('trans_array')) {
    /**
     * Get an array of translations for a given key
     * 
     * @param string $key Translation key that points to an array
     * @param array $params Parameters to replace in any string values within the array
     * @return array Array of translations with parameters replaced
     */
    function trans_array(string $key, array $params = []): array {
        return \Webklex\CalMag\Translator::getArray($key, $params);
    }
}

if(!function_exists('pretty_number')) {
    function pretty_number(mixed $value, string $unit, int $decimals = 2, ?string $decimal_separator = '.', ?string $thousands_separator = ''): string {
        // Handle null values
        if ($value === null) {
            $value = 0;
        }
        
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