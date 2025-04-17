<?php

namespace Webklex\CalMag;

/**
 * Configuration management class implementing the Singleton pattern
 * 
 * Handles loading and accessing configuration values from PHP files.
 * Provides static and instance methods for retrieving configuration values
 * using dot notation (e.g., 'app.models.linear').
 *
 * @package Webklex\CalMag
 * @method static mixed get(string $key, mixed $default = null) Get a configuration value by key
 */
class Config {

    /**
     * @var array Loaded configuration values
     */
    protected array $config = [];

    /**
     * @var Config|null Singleton instance
     */
    private static ?Config $instance = null;

    /**
     * Constructor that triggers configuration loading
     */
    public function __construct() {
        $this->load();
    }

    /**
     * Handle static method calls
     * 
     * Currently only supports the 'get' method for retrieving configuration values.
     * 
     * @param string $name Method name
     * @param array $arguments Method arguments
     * @return mixed Configuration value or null
     */
    public static function __callStatic($name, $arguments) {
        return match ($name) {
            'get' => self::getInstance()->get(...$arguments),
            default => null,
        };
    }

    /**
     * Handle instance method calls
     * 
     * Currently only supports the 'get' method for retrieving configuration values.
     * 
     * @param string $name Method name
     * @param array $arguments Method arguments
     * @return mixed Configuration value or null
     */
    public function __call($name, $arguments) {
        return match ($name) {
            'get' => $this->config(...$arguments),
            default => null,
        };
    }

    /**
     * Get or create the singleton instance
     * 
     * @return Config The singleton instance
     */
    public static function getInstance(): Config {
        if(self::$instance === null){
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * Load configuration files
     * 
     * Loads all PHP files from the config directory and stores their values
     * in the config array.
     * 
     * @return void
     */
    private function load(): void {
        $config = [];
        // Load all config files
        foreach (glob(__DIR__ . '/config/*.php') as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $config[$name] = require $file;
        }
        $this->config = $config;
    }

    /**
     * Get a configuration value using dot notation
     * 
     * Allows accessing nested configuration values using dot notation.
     * For example: 'app.models.linear' will access $config['app']['models']['linear']
     * 
     * @param string $key Configuration key in dot notation
     * @param mixed $default Default value if key not found
     * @return mixed Configuration value or default
     */
    public function config(string $key, $default = null) {
        $parts = explode('.', $key);
        $config = $this->config;
        foreach ($parts as $part) {
            if (isset($config[$part])) {
                $config = $config[$part];
            } else {
                return $default;
            }
        }
        return $config;
    }
}