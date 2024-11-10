<?php
/*
* File: Translator.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 22:53
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\CalMag;

class Translator {

    private string $locale;
    private array $translations = [];
    private static self $instance;

    public function __construct(string $locale = "en") {
        $this->locale = $locale;
        $this->load();
    }

    public static function getInstance(): self {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function translate(string $key, array $params = []): string {
        return self::getInstance()->get($key, $params);
    }

    public function load(): void {
        $locale = substr(array_map(function($locale) {
            return explode("-", explode(",", $locale)[1])[0];
        }, array_filter(explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE']), function($locale) {
            return str_contains($locale, ",");
        }))[0] ?? "en", 0, 2);

        $translationFile = __DIR__ . '/../resources/lang/' . $locale . '.php';
        if(file_exists($translationFile)) {
            $this->locale = $locale;
        }else{
            $this->locale = "en";
        }
        $translations = include __DIR__ . '/../resources/lang/' . $this->locale . '.php';
        //dot notation
        $this->translations = $this->array_dot($translations);
    }

    public function get(string $key, array $params = []): string {
        $translation = $this->translations[$key] ?? $key;
        return $this->replaceParams($translation, $params);
    }

    private function replaceParams(string $translation, array $params): string {
        foreach ($params as $key => $value) {
            $translation = str_replace(':' . $key, $value, $translation);
        }
        return $translation;
    }

    private function array_dot(mixed $translations, $key = ""): array {
        $result = [];
        foreach ($translations as $k => $v) {
            if(is_array($v)) {
                $result = array_merge($result, $this->array_dot($v, $key . $k . "."));
            }else{
                $result[$key . $k] = $v;
            }
        }
        return $result;
    }

}