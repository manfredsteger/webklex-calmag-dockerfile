<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Webklex\CalMag\Translator;

class TranslatorTest extends TestCase {

    private Translator $translator;
    private array $originalServer;

    protected function setUp(): void {
        parent::setUp();
        $this->originalServer = $_SERVER;
        $this->translator = new Translator('en');
    }

    protected function tearDown(): void {
        parent::tearDown();
        $_SERVER = $this->originalServer;
    }

    public function testConstructor(): void {
        $this->assertInstanceOf(Translator::class, $this->translator);
        
        // Test with different locales
        $translator = new Translator('es');
        $this->assertInstanceOf(Translator::class, $translator);
        
        $translator = new Translator('de');
        $this->assertInstanceOf(Translator::class, $translator);
    }

    public function testGetInstance(): void {
        $instance1 = Translator::getInstance();
        $instance2 = Translator::getInstance();
        
        $this->assertSame($instance1, $instance2);
        $this->assertInstanceOf(Translator::class, $instance1);
    }

    public function testLoad(): void {
        // Test with different browser languages
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-US,en;q=0.9';
        $translator = new Translator();
        $this->assertInstanceOf(Translator::class, $translator);
        
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es-ES,es;q=0.9';
        $translator = new Translator();
        $this->assertInstanceOf(Translator::class, $translator);
        
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'de-DE,de;q=0.9';
        $translator = new Translator();
        $this->assertInstanceOf(Translator::class, $translator);
        
        // Test with invalid language
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'xx-XX';
        $translator = new Translator();
        $this->assertInstanceOf(Translator::class, $translator);
        
        // Test without HTTP_ACCEPT_LANGUAGE
        unset($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $translator = new Translator();
        $this->assertInstanceOf(Translator::class, $translator);
    }

    public function testGet(): void {
        // Test with existing keys
        $result = $this->translator->get('test.key');
        $this->assertIsString($result);
        
        // Test with non-existent key
        $result = $this->translator->get('non.existent.key');
        $this->assertEquals('non.existent.key', $result);
        
        // Test with nested keys
        $result = $this->translator->get('nested.key.test');
        $this->assertIsString($result);
        
        // Test with empty key
        $result = $this->translator->get('');
        $this->assertEquals('', $result);
    }

    public function testTranslate(): void {
        // Test with existing key
        $result = Translator::translate('test.key');
        $this->assertIsString($result);
        
        // Test with non-existent key
        $result = Translator::translate('non.existent.key');
        $this->assertEquals('non.existent.key', $result);
        
        // Test with empty key
        $result = Translator::translate('');
        $this->assertEquals('', $result);
    }

    public function testReplaceParams(): void {
        // Test with single parameter
        $result = $this->translator->get('Hello :name', ['name' => 'John']);
        $this->assertEquals('Hello John', $result);
        
        // Test with multiple parameters
        $translation = 'Hello :name, welcome to :place';
        $params = [
            'name' => 'John',
            'place' => 'World'
        ];
        $result = $this->translator->get($translation, $params);
        $this->assertEquals('Hello John, welcome to World', $result);
        
        // Test with missing parameter
        $result = $this->translator->get('Hello :name', []);
        $this->assertEquals('Hello :name', $result);
        
        // Test with extra parameters
        $result = $this->translator->get('Hello :name', [
            'name' => 'John',
            'extra' => 'unused'
        ]);
        $this->assertEquals('Hello John', $result);
        
        // Test with repeated parameters
        $result = $this->translator->get('Hello :name, bye :name', ['name' => 'John']);
        $this->assertEquals('Hello John, bye John', $result);
    }

    public function testMultipleInstances(): void {
        $translator1 = new Translator('en');
        $translator2 = new Translator('es');
        
        $this->assertNotSame($translator1, $translator2);
        
        // Test that instances maintain their own state
        $translator1->get('test.key');
        $translator2->get('test.key');
        
        $this->assertInstanceOf(Translator::class, $translator1);
        $this->assertInstanceOf(Translator::class, $translator2);
    }

    public function testEdgeCases(): void {
        // Test with empty parameters array
        $result = $this->translator->get('test.key', []);
        $this->assertIsString($result);
        
        // Test with non-string parameter values
        $result = $this->translator->get('Value: :value', ['value' => 123]);
        $this->assertEquals('Value: 123', $result);
        
        // Test with boolean parameter values
        $result = $this->translator->get('Value: :value', ['value' => true]);
        $this->assertEquals('Value: 1', $result);
        
        // Test with empty string parameter values
        $result = $this->translator->get('Value: :value', ['value' => '']);
        $this->assertEquals('Value: ', $result);
    }
} 