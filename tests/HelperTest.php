<?php
/*
* File: ConfigTest.php
* Category: -
* Author: M.Goldenbaum
* Created: 28.12.22 18:11
* Updated: -
*
* Description:
*  -
*/


namespace Tests;

use PHPUnit\Framework\TestCase;
use Webklex\CalMag\Config;
use Webklex\CalMag\Enums\GrowState;
use Webklex\CalMag\Translator;

class HelperTest extends TestCase {

    /**
     * Config test
     *
     * @return void
     */
    public function testArrayDot(): void {
        $array = [
            'foo' => [
                'bar' => 'baz'
            ]
        ];
        $product = array_dot($array);
        self::assertSame($product, ['foo.bar' => 'baz']);
    }

    public function testTranslation() {
        $str = "region.default";
        self::assertSame("us", translate($str));
        self::assertSame("us", __($str));
        self::assertStringContainsString("foo", __("content.calculator.missing", ["value" => "foo", "name" => "bar"]));
        self::assertStringContainsString("bar", __("content.calculator.missing", ["value" => "foo", "name" => "bar"]));
        self::assertStringContainsString("12.567", __("content.calculator.missing", ["value" => 12.567, "name" => "bar"]));

        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7";
        Translator::getInstance()->load();
        self::assertSame("de", __($str));

        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "es-ES,de;q=0.9,en-US;q=0.8,en;q=0.7";
        Translator::getInstance()->load();
        self::assertSame("us", __($str));
    }

    public function testPrettyNumber() {
        $value = 124.567;
        self::assertSame("124.57 ml", pretty_number($value, "ml"));
        self::assertSame("1.25 l", pretty_number($value*10, "ml"));
        self::assertSame("124.57 g", pretty_number($value, "g"));
        self::assertSame("1.25 g", pretty_number($value*10, "mg"));
        self::assertSame("1.25 kg", pretty_number($value*10, "g"));
        self::assertSame("124.57 mg", pretty_number($value/1000, "g"));
        self::assertSame("124.57 g", pretty_number($value/1000, "kg"));
        self::assertSame("0.12 ml", pretty_number($value/1000, "ml"));
        self::assertSame("124.57 ml", pretty_number($value/1000, "l"));
        self::assertSame("124.57 foo", pretty_number($value, "foo"));
    }

    public function testGroState() {
        $states = GrowState::getStates();
        $labels = GrowState::getLabels();
        foreach($states as $key => $state) {
            self::assertSame($state->value, $labels[$key]);
            self::assertSame($state, GrowState::fromString($state->value));
        }
    }
}