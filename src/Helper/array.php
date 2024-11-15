<?php

if(!function_exists('array_dot')) {
    function array_dot(mixed $translations, $key = ""): array {
        $result = [];
        foreach ($translations as $k => $v) {
            if(is_array($v)) {
                $result = array_merge($result, array_dot($v, $key . $k . "."));
            }else{
                $result[$key . $k] = $v;
            }
        }
        return $result;
    }
}