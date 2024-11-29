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

class ConfigTest extends TestCase {

    /**
     * Config test
     *
     * @return void
     */
    public function testConfig(): void {
        self::assertInstanceOf(Config::class, Config::getInstance());
    }

    public function testArrayAccessor(): void {
        $additives = Config::get("additives", null);
        self::assertIsArray($additives);
        self::assertArrayHasKey("calcium", $additives);
        self::assertArrayHasKey("magnesium", $additives);

        self::assertIsArray($additives["calcium"]);
    }

    public function testDotAccessor(): void {
        self::assertIsArray(Config::get("additives.magnesium", null));
    }

    public function testAccessor(): void {
        self::assertSame("fooBar", Config::get("foo.bar", "fooBar"));
    }
}