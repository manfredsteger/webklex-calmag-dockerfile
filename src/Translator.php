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

/**
 * Translation management class implementing the Singleton pattern
 * 
 * Handles loading and accessing translations from language files.
 * Supports automatic language detection from HTTP headers and
 * parameter replacement in translation strings.
 *
 * @package Webklex\CalMag
 */
class Translator {

    /**
     * @var string Current locale code (e.g., 'en', 'de')
     */
    private string $locale;

    /**
     * @var array Loaded translations
     */
    private array $translations = [];

    /**
     * @var self Singleton instance
     */
    private static self $instance;

    /**
     * Constructor that initializes the translator with a locale
     * 
     * @param string $locale Default locale code (default: 'en')
     */
    public function __construct(string $locale = "en") {
        $this->locale = $locale;
        $this->load();
    }

    /**
     * Get or create the singleton instance
     * 
     * @return self The singleton instance
     */
    public static function getInstance(): self {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Static method to translate a key with optional parameters
     * 
     * @param string $key Translation key
     * @param array $params Parameters to replace in the translation
     * @return string Translated text
     */
    public static function translate(string $key, array $params = []): string {
        return self::getInstance()->get($key, $params);
    }

    /**
     * Load translations for the current locale
     * 
     * Attempts to detect the user's preferred language from HTTP headers
     * and falls back to English if the language file is not found.
     * 
     * @return void
     */
    public function load(): void {
        $locale = substr(array_map(function($locale) {
            return explode("-", explode(",", $locale)[0])[0];
        }, array_filter(explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ""), function($locale) {
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
        $this->translations = array_dot($translations);
    }

    /**
     * Get a translation by key with optional parameter replacement
     * 
     * @param string $key Translation key
     * @param array $params Parameters to replace in the translation
     * @return string Translated text
     */
    public function get(string $key, array $params = []): string {
        $translation = $this->translations[$key] ?? $key;
        return $this->replaceParams($translation, $params);
    }

    /**
     * Replace parameters in a translation string
     * 
     * Replaces placeholders in the format :paramName with their values
     * 
     * @param string $translation Translation string with placeholders
     * @param array $params Parameters to replace
     * @return string Translation with replaced parameters
     */
    private function replaceParams(string $translation, array $params): string {
        foreach ($params as $key => $value) {
            $translation = str_replace(':' . $key, $value, $translation);
        }
        return $translation;
    }

}