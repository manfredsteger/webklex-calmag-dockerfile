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
     * @var array Raw translations before dot notation conversion
     */
    private array $raw_translations = [];

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
     * Static method to get an array of translations for a given key
     * 
     * @param string $key Translation key that points to an array
     * @param array $params Parameters to replace in any string values within the array
     * @return array Array of translations with parameters replaced
     */
    public static function getArray(string $key, array $params = []): array {
        return self::getInstance()->getTranslationArray($key, $params);
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
        $this->raw_translations = include __DIR__ . '/../resources/lang/' . $this->locale . '.php';
        //dot notation
        $this->translations = array_dot($this->raw_translations);
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
     * Get an array of translations for a given key
     * 
     * @param string $key Translation key that points to an array
     * @param array $params Parameters to replace in any string values within the array
     * @return array Array of translations with parameters replaced
     */
    public function getTranslationArray(string $key, array $params = []): array {
        // Check if key exists in dot notation
        $parentKey = $key . '.';
        $arrayTranslations = array_filter($this->translations, function($k) use ($parentKey) {
            return str_starts_with($k, $parentKey);
        }, ARRAY_FILTER_USE_KEY);

        if (empty($arrayTranslations)) {
            return [];
        }

        // Convert filtered dot notation back to nested array
        $result = array_undot($arrayTranslations);
        $parts = explode('.', $key);
        
        // Navigate to the correct nesting level
        foreach ($parts as $part) {
            if (isset($result[$part])) {
                $result = $result[$part];
            } else {
                return [];
            }
        }
        
        // Replace parameters in all string values recursively
        return $this->replaceParamsRecursive($result, $params);
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

    /**
     * Recursively replace parameters in all string values within an array
     * 
     * @param mixed $value The value to process (array or string)
     * @param array $params Parameters to replace
     * @return mixed The processed value with parameters replaced in strings
     */
    private function replaceParamsRecursive(mixed $value, array $params): mixed {
        if (is_string($value)) {
            return $this->replaceParams($value, $params);
        }
        
        if (is_array($value)) {
            return array_map(
                fn($item) => $this->replaceParamsRecursive($item, $params),
                $value
            );
        }
        
        return $value;
    }

    /**
     * Get the raw translations array before dot notation conversion
     * 
     * @return array Raw translations
     */
    public function getRawTranslations(): array {
        return $this->raw_translations;
    }
}