<?php

namespace Webklex\CalMag;

/**
 * Class Config
 *
 * @package Webklex\CalMag
 * @method static mixed get(string $key, mixed $default = null)
 */
class Config {

    protected array $config = [];

    private static ?Config $instance = null;

    public function __construct() {
        $this->load();
    }

    public static function __callStatic($name, $arguments) {
        return match ($name) {
            'get' => self::getInstance()->get(...$arguments),
            default => null,
        };
    }

    public function __call($name, $arguments) {
        return match ($name) {
            'get' => $this->config(...$arguments),
            default => null,
        };
    }

    public static function getInstance(): Config {
        if(self::$instance === null){
            self::$instance = new Config();
        }
        return self::$instance;
    }

    private function load(): void {
        $config = [];
        // Load all config files
        foreach (glob(__DIR__ . '/config/*.php') as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $config[$name] = require $file;
        }
        $this->config = $config;
    }

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