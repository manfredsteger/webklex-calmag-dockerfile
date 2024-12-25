<?php

namespace Webklex\CalMag;

use Exception;

/**
 * Class Controller
 *
 * @package Webklex\CalMag
 */
class Controller {

    private Calculator $calculator;

    private array $elements;

    protected array $available_elements;

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
    private array $additive_concentration;
    private array $additive_units;
    private float $ratio = 3.5;
    private float $density = 1.0;

    private float $volume = 5.0;
    private bool $support_dilution = true;
    private float $target_offset = 0.0;

    private string $region = "us";

    private array $regions;

    private bool $validated = false;

    /**
     * Controller constructor.
     */
    public function __construct() {
        $this->elements = Config::get("app.elements");
        $this->regions = Config::get("app.regions");
        $this->available_elements = Config::get("app.available_elements");

        $region = Translator::translate("region.default");
        if (isset($this->regions[$region])) {
            $this->region = $region;
        }

        $this->loadCalculator();
    }

    /**
     * Load the Calculator instance and set the default additive concentrations for both magnesium and calcium based on
     * the first additive.
     * @return void
     */
    private function loadCalculator(): void {
        $this->calculator = new Calculator(["elements" => $this->elements], $this->fertilizer, $this->additive, $this->ratio);

        $additives = $this->calculator->getAdditives();
        $this->additive_concentration = [
            "magnesium" => $additives["magnesium"][array_key_first($additives["magnesium"])]["concentration"],
            "calcium"   => $additives["calcium"][array_key_first($additives["calcium"])]["concentration"],
        ];
        $this->additive_units = [
            "magnesium" => $additives["magnesium"][array_key_first($additives["magnesium"])]["unit"] ?? "mg",
            "calcium"   => $additives["calcium"][array_key_first($additives["calcium"])]["unit"] ?? "mg",
        ];
    }

    /**
     * Render the index page - the calculator form, without any result
     * @return void
     */
    public function index(): void {
        $this->render(function() {
            $form = [
                "fertilizer"             => array_key_first($this->calculator->getFertilizers()),
                "additive"               => [
                    "magnesium" => array_key_first($this->calculator->getAdditives()["magnesium"]),
                    "calcium"   => array_key_first($this->calculator->getAdditives()["calcium"]),
                ],
                "ratio"                  => $this->ratio,
                "volume"                 => $this->volume,
                "support_dilution"       => $this->support_dilution,
                "target_offset"          => $this->target_offset,
                "region"                 => $this->region,
                "elements"               => $this->elements,
                "element_units"          => $this->element_units,
                "additive_concentration" => $this->additive_concentration,
                "additive_units" => $this->additive_units,
            ];
            return [
                "form"               => $form,
                "regions"            => $this->regions,
                "available_elements" => $this->available_elements,
                "calculator"         => $this->calculator,
            ];
        });
    }

    /**
     * Render the result page - the calculator form with the result
     * @param array $payload The POST payload
     * @return void
     */
    public function result(array $payload): void {
        try {
            $this->validate($payload);
        } catch (Exception $e) {
            $this->validated = false;
            $this->index();
            return;
        }
        $this->render(function() {
            $form = [
                "fertilizer"             => $this->fertilizer !== "" ? $this->fertilizer : $this->calculator->getFertilizer(),
                "additive"               => count($this->additive) > 0 ? $this->additive : $this->calculator->getAdditive(),
                "ratio"                  => $this->ratio,
                "volume"                 => $this->volume,
                "support_dilution"       => $this->support_dilution,
                "target_offset"          => $this->target_offset,
                "region"                 => $this->region,
                "elements"               => $this->elements,
                "element_units"          => $this->element_units,
                "show_suggestions"       => (bool)($_GET["show_suggestions"] ?? false),
                "additive_concentration" => $this->additive_concentration,
                "additive_units" => $this->additive_units,
            ];
            return [
                "form"               => $form,
                "result"             => $this->validated ? $this->calculator->calculate() : null,
                "regions"            => $this->regions,
                "available_elements" => $this->available_elements,
                "calculator"         => $this->calculator,
            ];
        });
    }

    public function compare(array $payload): void {
        $valid_elements = ["calcium", "magnesium"];
        $_elements = $payload['elements'] ?? $this->elements;
        $_element_units = $payload['element_units'] ?? $this->element_units;

        if (!is_array($_elements)) {
            $_elements = $this->elements;
        }
        if (!is_array($_element_units)) {
            $_element_units = $this->element_units;
        }

        $elements = [];
        $element_units = [];
        foreach ($valid_elements as $element) {
            if (!isset($_elements[$element])) {
                $_elements[$element] = 0;
            }
            if (!isset($_element_units[$element])) {
                $_element_units[$element] = "mg";
            }
            $elements[$element] = (float)$_elements[$element];
            $element_units[$element] = match (strtolower($_element_units[$element])) {
                "ml", "mg" => $_element_units[$element],
                default => "mg",
            };
        }

        $this->elements = $this->convertElements(array_merge($this->elements, $elements), array_merge($this->element_units, $element_units));

        $comparator = new Comparator($this->elements, $this->ratio);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->validated = true;
        }

        $this->render(function() use ($comparator) {
            $form = [
                "ratio"         => $this->ratio,
                "elements"      => $this->elements,
                "element_units" => $this->element_units,
            ];
            return [
                "form"               => $form,
                "result"             => $this->validated ? $comparator->calculate() : null,
                "available_elements" => $this->available_elements,
                "comparator"         => $comparator,
            ];
        }, "compare");
    }

    public function builder(array $payload): void {
        try {
            $this->validate($payload);
        } catch (Exception $e) {
            $this->validated = false;
        }

        $this->render(function() {
            $form = [
                "additive"               => count($this->additive) > 0 ? $this->additive : $this->calculator->getAdditive(),
                "ratio"                  => $this->ratio,
                "density"                  => $this->density,
                "elements"               => $this->elements,
                "element_units"          => $this->element_units,
                "show_suggestions"       => true,
            ];
            return [
                "form"               => $form,
                "result"             => $this->validated ? $this->calculator->calculate() : null,
                "available_elements" => $this->available_elements,
                "calculator"         => $this->calculator,
            ];
        }, "builder");
    }

    /**
     * Render the API response
     * @param array $payload The input payload
     * @return void
     */
    public function api(array $payload): void {
        try {
            $this->validate($payload);
        } catch (Exception $e) {
            $this->validated = false;
            $message = $e->getMessage();
        }

        try{
            header('Content-Type: application/json');
        } catch (Exception $e) {
            // Handle the exception
        }
        if (!$this->validated) {
            echo json_encode([
                                 "error" => $message ?? "Invalid input",
                             ]);
            try{
                http_response_code(400);
            } catch (Exception $e) {
                // Handle the exception
            }
            return;
        }
        echo json_encode([
                             "version" => Application::VERSION,
                             "result"  => $this->calculator->calculate(),
                         ]);
    }

    /**
     * Render the view
     * @param callable $callback The callback function
     * @param string $view The view to be rendered
     * @return void
     */
    private function render(callable $callback, string $view = "calculator"): void {
        include __DIR__ . '/../resources/views/header.phtml';

        extract($callback());

        include __DIR__ . '/../resources/views/'.$view.'.phtml';

        include __DIR__ . '/../resources/views/footer.phtml';
    }

    /**
     * Validate and congest a given payload
     * @throws Exception
     */
    private function validate(array $payload): void {
        // Validate the input
        $fertilizer = $payload['fertilizer'] ?? "";
        $additive = $payload['additive'] ?? [];
        $region = $payload['region'] ?? $this->region;
        $ratio = $payload['ratio'] ?? $this->ratio;
        $density = $payload['density'] ?? $this->density;
        $volume = $payload['volume'] ?? $this->volume;
        $support_dilution = $payload['support_dilution'] ?? $this->support_dilution;
        $target_offset = $payload['target_offset'] ?? $this->target_offset;
        $elements = $payload['elements'] ?? $this->elements;
        $element_units = $payload['element_units'] ?? $this->element_units;
        $additive_concentration = $payload['additive_concentration'] ?? [];
        $additive_units = $payload['additive_units'] ?? [];

        if (!is_string($fertilizer) || !is_array($additive) || !is_array($elements) || !is_array($additive_concentration) || !is_array($additive_units)) {
            throw new Exception("Invalid input");
        }
        if (!isset($this->calculator->getFertilizers()[$fertilizer]) && $fertilizer !== "") {
            throw new Exception("Invalid fertilizer");
        }
        $_additives = $this->calculator->getAdditives();
        foreach ($additive as $elm => $value) {
            if (!isset($_additives[$elm]) || (!isset($_additives[$elm][$value]) && $value !== "")) {
                throw new Exception("Invalid additive");
            }
        }
        if ($ratio <= 0) {
            throw new Exception("Invalid ratio");
        }
        if ($density <= 0) {
            throw new Exception("Invalid density");
        }
        if ($volume <= 0) {
            throw new Exception("Invalid volume");
        }
        if ($target_offset < -100.0 || $target_offset > 100.0) {
            throw new Exception("Invalid target_offset");
        }
        if (!isset($this->regions[$region])) {
            throw new Exception("Invalid region");
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
                throw new Exception("Invalid element");
            }
            $elements[$element] = (float)$value;
        }
        foreach ($additive_concentration as $element => $concentration) {
            if (!in_array($element, $_elements)) {
                throw new Exception("Invalid element");
            }
            $additive_concentration[$element] = (float)$concentration;
        }
        foreach ($additive_units as $element => $unit) {
            if (!in_array($element, $_elements)) {
                throw new Exception("Invalid element");
            }
            $additive_units[$element] = match (strtolower($unit)) {
                "ml", "mg" => $unit,
                default => "mg",
            };
            if($additive_units[$element] === "mg") {
                $additive_concentration[$element] = 100;
            }
        }

        if(!is_bool($support_dilution)){
            $support_dilution = match (strtolower($support_dilution)) {
                "true", "on", "yes", "1" => true,
                default => false,
            };
        }

        $this->calculator->setDilutionSupport($support_dilution);

        $this->fertilizer = $fertilizer;
        $this->additive = $additive;
        $this->ratio = $ratio;
        $this->density = $density;
        $this->volume = $volume;
        $this->support_dilution = $support_dilution;
        $this->target_offset = $target_offset;
        $this->region = $region;
        $this->additive_concentration = $additive_concentration;
        $this->additive_units = $additive_units;

        $this->elements = $this->convertElements([
                                                     ...$this->elements,
                                                     ...$elements,
                                                 ], [
                                                     ...$this->element_units,
                                                     ...$element_units,
                                                 ]);
        $this->validated = true;


        try {
            $this->calculator->setFertilizer($this->fertilizer);
            $this->calculator->setAdditive($this->additive, $this->additive_concentration);
            $this->calculator->setRatio($this->ratio, 1.0);
            $this->calculator->setWater(["elements" => $this->elements]);
        } catch (Exception $e) {
            // Handle the exception
        }
    }

    /**
     * Convert a list of given elements to mg/L
     * @param array $elements The elements to be converted
     * @param array $element_units The units of the elements
     * @return array
     */
    private function convertElements(array $elements, array $element_units): array {
        $elements = array_unique($elements);
        // convert the elements to mg/L from the given units
        foreach ($elements as $element => $value) {
            $unit = $element_units[$element] ?? "mg";
            $elements[$element] = match ($unit) {
                "mmol" => match ($element) {
                    "calcium" => $value * 40.08,
                    "magnesium" => $value * 24.31,
                    "potassium" => $value * 39.10,
                    "iron" => $value * 55.85,
                    "sulphate" => $value * 96.06,
                    "nitrate" => $value * 62.00,
                    "nitrite" => $value * 46.01,
                    "phosphorus" => $value * 30.97,
                    "nitrogen" => $value * 14.01,
                    "sulfur" => $value * 32.06,
                    "sodium" => $value * 22.99,
                    "chloride" => $value * 35.45,
                    "manganese" => $value * 54.94,
                    "boron" => $value * 10.81,
                    "zinc" => $value * 65.38,
                    "copper" => $value * 63.55,
                    "molybdenum" => $value * 95.94,
                    default => $value,
                },
                default => $value,
            };
        }
        return $elements;
    }
}