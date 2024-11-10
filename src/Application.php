<?php
/*
* File: Application.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 19:29
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\CalMag;

class Application {

    private CalMagCalculator $calculator;

    const VERSION = "1.0.0";

    private array $elements = [
        "calcium"   => 61.0, // mg/L
        "magnesium" => 4.6, // mg/L
        "potassium" => 5.0, // mg/L
        "iron"      => 0.2, // mg/L
        "sulphate"  => 75.0, // mg/L
        "nitrate"   => 2.8, // mg/L
        "nitrite"   => 0.01, // mg/L
    ];

    private string $fertilizer = "";
    private string $additive = "";
    private float $ratio = 3.5;

    private string $region = "us";

    private array $regions = [
        "us" => "region.option.us",
        "de" => "region.option.de",
    ];

    private bool $validated = false;

    public function __construct() {
        require_once __DIR__ . '/Helper/translation.php';
        $this->calculator = new CalMagCalculator([
                                                     "elements" => $this->elements,
                                                 ], $this->fertilizer, $this->additive, $this->ratio);
        $region = Translator::translate("region.default");
        if(isset($this->regions[$region])) {
            $this->region = $region;
        }
    }

    public function load(): void {
        // Check if the current request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($_SERVER["CONTENT_TYPE"] === "application/json") {
                $_POST = json_decode(file_get_contents('php://input'), true);
            }
            try{
                $this->validate();
                $this->calculator->setFertilizer($this->fertilizer);
                $this->calculator->setAdditive($this->additive);
                $this->calculator->setRatio($this->ratio, 1.0);
                $this->calculator->setWater([
                                                "elements" => $this->elements,
                                            ]);
            } catch (\Exception $e) {
                // Handle the exception
            }
        }
    }

    private function validate(): void {
        // Validate the input
        $fertilizer = $_POST['fertilizer'] ?? "";
        $additive = $_POST['additive'] ?? "";
        $region = $_POST['region'] ?? $this->region;
        $ratio = $_POST['ratio'] ?? $this->ratio;
        $elements = $_POST['elements'] ?? $this->elements;

        if (!is_string($fertilizer) || !is_string($additive) || !is_array($elements)) {
            throw new \Exception("Invalid input");
        }
        if (!isset($this->calculator->getFertilizers()[$fertilizer])) {
            throw new \Exception("Invalid fertilizer");
        }
        if (!isset($this->calculator->getAdditives()[$additive])) {
            throw new \Exception("Invalid additive");
        }
        if ($ratio <= 0) {
            throw new \Exception("Invalid ratio");
        }
        if (!isset($this->regions[$region])) {
            throw new \Exception("Invalid region");
        }
        $_elements = [
            ...$this->calculator->getElements(),
            "sulphate",
            "nitrate",
            "nitrite",
        ];
        foreach ($elements as $element => $value) {
            if (!in_array($element, $_elements)) {
                throw new \Exception("Invalid element");
            }
            $elements[$element] = (float)$value;
        }

        $this->fertilizer = $fertilizer;
        $this->additive = $additive;
        $this->ratio = $ratio;
        $this->region = $region;
        $this->elements = [
            ...$this->elements,
            ...$elements,
        ];
        $this->validated = true;
    }

    public function render(): void {
        if($_SERVER["CONTENT_TYPE"] === "application/json") {
            header('Content-Type: application/json');
            if(!$this->validated) {
                echo json_encode([
                                    "error" => "Invalid input",
                                ]);
                http_response_code(400);
                return;
            }
            echo json_encode([
                                "version" => self::VERSION,
                                "elements" => $this->elements,
                                "fertilizer" => $this->fertilizer,
                                "additive" => $this->additive,
                                "ratio" => $this->ratio,
                                "region" => $this->region,
                                "regions" => $this->regions,
                                "result" => $this->calculator->calculate(),
                            ]);
            return;
        }
        include __DIR__ . '/../resources/views/header.phtml';

        $calculator = $this->calculator;
        $result = $this->validated ? $calculator->calculate() : null;
        $regions = $this->regions;

        $form = [
            "fertilizer" => $this->fertilizer !== "" ?$this->fertilizer: $calculator->getFertilizer(),
            "additive"   => $this->additive !== "" ?$this->additive: $calculator->getAdditive(),
            "ratio"      => $this->ratio,
            "region"     => $this->region,
            "elements"   => $this->elements,
        ];

        include __DIR__ . '/../resources/views/calculator_result.phtml';

        include __DIR__ . '/../resources/views/footer.phtml';
    }

}