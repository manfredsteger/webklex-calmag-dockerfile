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
use Webklex\CalMag\Calculator;
use Webklex\CalMag\Config;
use Webklex\CalMag\Controller;

class ControllerTest extends TestCase {

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['HTTP_HOST'] = 'localhost';
    }

    /**
     * Config test
     *
     * @return void
     */
    public function testController(): void {
        $controller = new Controller();
        self::assertInstanceOf(Controller::class, $controller);
    }

    public function testIndex() {
        $controller = new Controller();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        self::assertStringContainsString('</html>', $output);
    }

    public function testResult() {
        $controller = new Controller();
        $payload = [
            "fertilizer"             => "",
            "additive"               => [
                "calcium"   => "",
                "magnesium" => ""
            ],
            "additive_concentration" => [
                "calcium"   => 0,
                "magnesium" => 0
            ],
            "region"                 => "de",
            "ratio"                  => 3.5,
            "volume"                 => 5,
            "elements"               => [
                "calcium"   => 0,
                "magnesium" => 0,
                "sulphate"  => 0,
            ],
            "element_units"          => [
                "calcium"   => "mg",
                "magnesium" => "mg"
            ]
        ];
        ob_start();
        $controller->result($payload);
        $output = ob_get_clean();
        self::assertStringContainsString('CalMag calculation results', $output);

        ob_start();
        $controller->result(["fertilizer" => 47.11]);
        $output = ob_get_clean();
        self::assertStringNotContainsString('CalMag calculation results', $output);
    }

    public function testBuilder() {
        $controller = new Controller();
        $payload = [
            "additive"      => [
                "calcium"   => "",
                "magnesium" => ""
            ],
            "ratio"         => 3.5,
            "volume"        => 5,
            "elements"      => [
                "calcium"   => 0,
                "magnesium" => 0,
                "sulphate"  => 0,
            ],
            "element_units" => [
                "calcium"   => "mg",
                "magnesium" => "mg"
            ]
        ];
        ob_start();
        $controller->builder($payload);
        $output = ob_get_clean();
        self::assertStringContainsString('CalMag calculation results', $output);

        ob_start();
        $controller->builder(["fertilizer" => 47.11]);
        $output = ob_get_clean();
        self::assertStringNotContainsString('CalMag calculation results', $output);
    }

    public function testApi() {
        $controller = new Controller();
        $payload = [
            "fertilizer"             => "",
            "additive"               => [
                "calcium"   => "",
                "magnesium" => ""
            ],
            "additive_concentration" => [
                "calcium"   => 0,
                "magnesium" => 0
            ],
            "region"                 => "de",
            "ratio"                  => 3.5,
            "volume"                 => 5,
            "elements"               => [
                "calcium"   => 0,
                "magnesium" => 0,
                "sulphate"  => 0,
            ],
            "element_units"          => [
                "calcium"   => "mg",
                "magnesium" => "mg"
            ]
        ];
        ob_start();
        $controller->api($payload);
        $output = ob_get_clean();

        $json = json_decode($output, true);
        self::assertIsArray($json);
        self::assertArrayHasKey('version', $json);
        self::assertArrayHasKey('result', $json);
        self::assertArrayHasKey('deficiency', $json['result']);
        self::assertArrayHasKey('results', $json['result']);
        self::assertArrayHasKey('table', $json['result']);
    }

    public function testValidation() {
        $this->fail_query_api(['fertilizer' => 47.11]);
        $this->fail_query_api(['fertilizer' => []]);
        $this->fail_query_api(['additive' => 47.11]);
        $this->fail_query_api(['additive' => ""]);
        $this->fail_query_api(['elements' => 47.11]);
        $this->fail_query_api(['elements' => ""]);
        $this->fail_query_api(['additive_concentration' => 47.11]);
        $this->fail_query_api(['additive_concentration' => ""]);

        $this->fail_query_api(['fertilizer' => "foo", "elements" => []], 'Invalid fertilizer');
        $this->fail_query_api(['additive' => ["calcium" => "foo"], "elements" => []], 'Invalid additive');
        $this->fail_query_api(['ratio' => -4, "elements" => []], 'Invalid ratio');
        $this->fail_query_api(['density' => -4, "elements" => []], 'Invalid density');
        $this->fail_query_api(['volume' => -4, "elements" => []], 'Invalid volume');
        $this->fail_query_api(['region' => -4, "elements" => []], 'Invalid region');
        $this->fail_query_api(["elements" => ["foo" => 42]], 'Invalid element');
        $this->fail_query_api(["additive_concentration" => ["foo" => 42]], 'Invalid element');
    }

    private function fail_query_api(array $payload, string $error = 'Invalid input'): void {
        $json = $this->query_api($payload);
        self::assertIsArray($json);
        self::assertArrayHasKey('error', $json);
        self::assertSame($error, $json['error']);
    }

    private function query_api(array $payload) {
        $controller = new Controller();
        ob_start();
        $controller->api($payload);
        $output = ob_get_clean();
        return json_decode($output, true);
    }
}