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

if(!function_exists('array_undot')) {
    function array_undot(array $array): array {
        $result = [];
        foreach ($array as $key => $value) {
            $parts = explode('.', $key);
            $temp = &$result;
            
            foreach ($parts as $i => $part) {
                if ($i === count($parts) - 1) {
                    $temp[$part] = $value;
                } else {
                    if (!isset($temp[$part]) || !is_array($temp[$part])) {
                        $temp[$part] = [];
                    }
                    $temp = &$temp[$part];
                }
            }
        }
        return $result;
    }
}