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

    const VERSION = "1.4.0";

    private array $elements = [
        "calcium"   => 0.0, // mg/L
        "magnesium" => 0.0, // mg/L
        "potassium" => 0.0, // mg/L
        "iron"      => 0.0, // mg/L
        "sulphate"  => 0.0, // mg/L
        "nitrate"   => 0.0, // mg/L
        "nitrite"   => 0.0, // mg/L
    ];

    protected array $element_units = [
        "calcium"   => "mg",
        "magnesium" => "mg",
        "potassium" => "mg",
        "iron"      => "mg",
        "sulphate"  => "mg",
        "nitrate"   => "mg",
        "nitrite"   => "mg",
    ];

    private string $fertilizer = "";
    private array $additive = [
        "magnesium" => "",
        "calcium"   => "",
    ];
    private float $ratio = 3.5;

    private float $volume = 5.0;

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
        }elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $payload = $_GET["p"] ?? "";

            if($payload !== "") {
                $payload = base64_decode($payload);
                $payload = json_decode($payload, true);
                if(is_array($payload)) {
                    $_POST = [
                        "fertilizer" => $payload["fertilizer"] ?? "",
                        "additive"   => $payload["additive"] ?? [],
                        "ratio"      => $payload["ratio"] ?? 3.5,
                        "volume"     => $payload["volume"] ?? 5.0,
                        "region"     => $payload["region"] ?? "us",
                        "elements"   => $payload["elements"] ?? [],
                        "element_units"   => $payload["element_units"] ?? [],
                    ];
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
        }
    }

    private function validate(): void {
        // Validate the input
        $fertilizer = $_POST['fertilizer'] ?? "";
        $additive = $_POST['additive'] ?? [];
        $region = $_POST['region'] ?? $this->region;
        $ratio = $_POST['ratio'] ?? $this->ratio;
        $volume = $_POST['volume'] ?? $this->volume;
        $elements = $_POST['elements'] ?? $this->elements;
        $element_units = $_POST['element_units'] ?? $this->element_units;

        if (!is_string($fertilizer) || !is_array($additive) || !is_array($elements)) {
            throw new \Exception("Invalid input");
        }
        if (!isset($this->calculator->getFertilizers()[$fertilizer])) {
            throw new \Exception("Invalid fertilizer");
        }
        $_additives = $this->calculator->getAdditives();
        foreach($additive as $elm => $value) {
            if (!isset($_additives[$elm]) || !isset($_additives[$elm][$value])) {
                throw new \Exception("Invalid additive");
            }
        }
        if ($ratio <= 0) {
            throw new \Exception("Invalid ratio");
        }
        if ($volume <= 0) {
            throw new \Exception("Invalid volume");
        }
        if (!isset($this->regions[$region])) {
            throw new \Exception("Invalid region");
        }
        $_elements = [
            ...$this->calculator->getElements(),
            ...array_keys($this->elements),
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
        $this->volume = $volume;
        $this->region = $region;
        $elements = [
            ...$this->elements,
            ...$elements,
        ];
        $element_units = [
            ...$this->element_units,
            ...$element_units,
        ];
        // convert the elements to mg/L from the given units
        foreach($elements as $element => $value) {
            $unit = $element_units[$element] ?? "mg";
            $elements[$element] = match($unit) {
                "mmol" => match($element) {
                    "calcium"   => $value * 40.08,
                    "magnesium" => $value * 24.31,
                    "potassium" => $value * 39.10,
                    "iron"      => $value * 55.85,
                    "sulphate"  => $value * 96.06,
                    "nitrate"   => $value * 62.00,
                    "nitrite"   => $value * 46.01,
                    "phosphorus" => $value * 30.97,
                    "nitrogen"  => $value * 14.01,
                    "sulfur"    => $value * 32.06,
                    "sodium"    => $value * 22.99,
                    "chloride"  => $value * 35.45,
                    "manganese" => $value * 54.94,
                    "boron"     => $value * 10.81,
                    "zinc"      => $value * 65.38,
                    "copper"    => $value * 63.55,
                    "molybdenum" => $value * 95.94,
                    default     => $value,
                },
                default => $value,
            };
        }
        $this->elements = $elements;
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
            "additive"   => count($this->additive) > 0 ? $this->additive: $calculator->getAdditive(),
            "ratio"      => $this->ratio,
            "volume"     => $this->volume,
            "region"     => $this->region,
            "elements"   => $this->elements,
            "element_units"   => $this->element_units,
        ];

        include __DIR__ . '/../resources/views/calculator_result.phtml';

        include __DIR__ . '/../resources/views/footer.phtml';
    }

}