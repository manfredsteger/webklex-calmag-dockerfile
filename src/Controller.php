<?php

namespace Webklex\CalMag;

use Exception;
use Webklex\CalMag\Enums\GrowState;

/**
 * Main controller class handling all calculator operations and view rendering
 * 
 * This class manages the calculator instance, handles form submissions,
 * validates input data, and renders appropriate views. It supports different
 * calculation modes including standard calculation, comparison, and builder mode.
 *
 * @package Webklex\CalMag
 */
class Controller {

    /**
     * @var Calculator The calculator instance for nutrient calculations
     */
    private Calculator $calculator;

    /**
     * @var array The current element values for water composition
     */
    private array $elements;

    /**
     * @var array List of elements available for calculation
     */
    protected array $available_elements;

    /**
     * @var array Default units for each element type
     */
    protected array $element_units = [
        "calcium"   => "mg",
        "magnesium" => "mg",
        "potassium" => "mg",
        "iron"      => "mg",
        "sulphate"  => "mg",
        "nitrate"   => "mg",
        "nitrite"   => "mg",
    ];

    /**
     * @var string|null Currently selected fertilizer
     */
    private ?string $fertilizer = null;

    /**
     * @var array Currently selected additives for each element
     */
    private array $additive = [
        "magnesium" => "",
        "calcium"   => "",
    ];

    /**
     * @var array Concentration values for additives
     */
    private array $additive_concentration;

    /**
     * @var array Units for additive measurements
     */
    private array $additive_units;

    /**
     * @var float Calcium to magnesium ratio
     */
    private float $ratio = 3.5;

    /**
     * @var float Density factor for calculations
     */
    private float $density = 1.0;

    /**
     * @var string Current calculation model
     */
    protected string $target_model = "linear";

    /**
     * @var float Volume for calculations in liters
     */
    private float $volume = 5.0;

    /**
     * @var bool Whether to support water dilution
     */
    private bool $support_dilution = true;

    /**
     * @var float Offset applied to target values
     */
    private float $target_offset = 0.0;

    /**
     * @var string Current region setting
     */
    private string $region = "us";

    /**
     * @var array Available regions and their configurations
     */
    private array $regions;

    /**
     * @var array Element compositions for additives
     */
    private array $additive_elements = [];

    /**
     * @var array Element compositions for fertilizers
     */
    private array $fertilizer_elements = [];

    /**
     * @var array Target calcium values per growth stage
     */
    private array $target_calcium = [];

    /**
     * @var array Target magnesium values per growth stage
     */
    private array $target_magnesium = [];

    /**
     * @var bool Whether input data has been validated
     */
    private bool $validated = false;

    /**
     * Controller constructor
     * 
     * Initializes the controller with default values and configurations.
     * Loads elements, regions, and calculator instance.
     */
    public function __construct() {
        $this->elements = Config::get("app.elements");
        $this->regions = Config::get("app.regions");
        $this->available_elements = Config::get("app.available_elements");

        $region = Translator::translate("region.default");
        if (isset($this->regions[$region])) {
            $this->region = $region;
        }

        if (($_GET["expert"] ?? null)) {
            $this->available_elements = Config::get("app.expert_elements");
        }

        $this->loadCalculator();
    }

    /**
     * Load the Calculator instance and configure default additive settings
     * 
     * Initializes a new calculator instance and sets up default concentrations
     * and units for both magnesium and calcium additives.
     * 
     * @return void
     */
    private function loadCalculator(): void {
        $this->calculator = new Calculator(["elements" => $this->elements], (string)$this->fertilizer, $this->additive, $this->ratio);

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
     * Render the index page with the calculator form
     * 
     * Displays the initial calculator form without any calculation results.
     * Sets up default values and available options for the form.
     * 
     * @return void
     */
    public function index(): void {
        $this->render(function() {
            $fertilizers = $this->calculator->getFertilizers();
            $additives = $this->calculator->getAdditives();
            if($this->additive["magnesium"] === "" && $this->additive["calcium"] === "") {
                $this->additive["magnesium"] = array_key_first($additives["magnesium"]);
                $this->additive["calcium"] = array_key_first($additives["calcium"]);
            }elseif($this->additive_elements === []) {
                $this->additive_elements = [
                    "calcium"   => [
                        "calcium" => [
                            "calcium" => $additives["calcium"][$this->additive["calcium"]]["elements"]["calcium"] ?? 0,
                            "CaO"     => $additives["calcium"][$this->additive["calcium"]]["elements"]["calcium"]["CaO"] ?? 0,
                        ]
                    ],
                    "magnesium" => [
                        "magnesium" => [
                            "magnesium" => $additives["magnesium"][$this->additive["magnesium"]]["elements"]["magnesium"] ?? 0,
                            "MgO"      => $additives["magnesium"][$this->additive["magnesium"]]["elements"]["magnesium"]["MgO"] ?? 0,
                        ]
                    ],
                ];
            }
            $form = [
                "fertilizer"             => $this->fertilizer ?? array_key_first($fertilizers),
                "additive"               => $this->additive,
                "additive_elements"      => $this->additive_elements,
                "ratio"                  => $this->ratio,
                "volume"                 => $this->volume,
                "target_model"           => $this->target_model,
                "support_dilution"       => $this->support_dilution,
                "target_offset"          => $this->target_offset,
                "region"                 => $this->region,
                "elements"               => $this->elements,
                "element_units"          => $this->element_units,
                "additive_concentration" => $this->additive_concentration,
                "additive_units"         => $this->additive_units,
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
     * Process and render calculation results
     * 
     * Validates input data, performs calculations, and renders the result page
     * with both the form and calculation results.
     * 
     * @param array $payload The form submission data
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
                "target_model"           => $this->target_model,
                "region"                 => $this->region,
                "elements"               => $this->elements,
                "element_units"          => $this->element_units,
                "show_suggestions"       => (bool)($_GET["show_suggestions"] ?? false),
                "additive_concentration" => $this->additive_concentration,
                "additive_units"         => $this->additive_units,
                "additive_elements"      => $this->additive_elements,
                "fertilizer_elements"    => $this->fertilizer_elements,
                "target_calcium"         => $this->target_calcium,
                "target_magnesium"       => $this->target_magnesium,
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

    /**
     * Handle comparison mode calculations
     * 
     * Processes and displays comparisons between different fertilizers
     * for given water composition and target values.
     * 
     * @param array $payload The comparison configuration data
     * @return void
     */
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

    /**
     * Handle builder mode for custom nutrient solutions
     * 
     * Allows users to build custom nutrient solutions by specifying
     * exact compositions of fertilizers and additives.
     * 
     * @param array $payload The builder configuration data
     * @return void
     */
    public function builder(array $payload): void {
        try {
            $this->validate($payload);
        } catch (Exception $e) {
            $this->validated = false;
        }

        $this->render(function() {
            $form = [
                "additive"         => count($this->additive) > 0 ? $this->additive : $this->calculator->getAdditive(),
                "ratio"            => $this->ratio,
                "density"          => $this->density,
                "elements"         => $this->elements,
                "element_units"    => $this->element_units,
                "show_suggestions" => true,
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
     * @param string|null $method
     * @return void
     */
    public function api(array $payload, string $method = null): void {
        try {
            header('Content-Type: application/json');
        } catch (Exception $e) {
            // Handle the exception
        }
        $method = trim(strtolower($method));
        switch ($method) {
            case "models":
                echo json_encode([
                                     "version" => Application::VERSION,
                                     "models"  => Config::get("app.models", []),
                                 ]);
                break;
            case "additives":
                $additives = $this->calculator->getAdditives();
                foreach($additives as $element => $_additives) {
                    foreach ($_additives as $index => $additive) {
                        $additives[$element][$index]["name"] = __("additive.".$index);
                    }
                }
                echo json_encode([
                                     "version" => Application::VERSION,
                                     "additives"  => $additives,
                                 ]);
                break;
            case "fertilizers":
                echo json_encode([
                                     "version" => Application::VERSION,
                                     "fertilizers"  => $this->calculator->getFertilizers(),
                                 ]);
                break;
            case null: {
                try {
                    $this->validate($payload);
                } catch (Exception $e) {
                    $this->validated = false;
                    $message = $e->getMessage();
                }
                if (!$this->validated) {
                    echo json_encode([
                                         "error" => $message ?? "Invalid input",
                                     ]);
                    try {
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
                break;
            }
            default: {
                header("HTTP/1.1 404 Welp, that's not gonna work");
                exit(0);
            }
        }
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

        include __DIR__ . '/../resources/views/' . $view . '.phtml';

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
        $target_model = $payload['target_model'] ?? $this->target_model;
        $support_dilution = $payload['support_dilution'] ?? $this->support_dilution;
        $target_offset = $payload['target_offset'] ?? $this->target_offset;
        $elements = $payload['elements'] ?? $this->elements;
        $element_units = $payload['element_units'] ?? $this->element_units;
        $additive_concentration = $payload['additive_concentration'] ?? [];
        $additive_units = $payload['additive_units'] ?? [];
        $additive_elements = $payload['additive_elements'] ?? [];
        $fertilizer_elements = $payload['fertilizer_elements'] ?? [];
        $target_calcium = $payload['target_calcium'] ?? [];
        $target_magnesium = $payload['target_magnesium'] ?? [];

        if (($_GET["expert"] ?? null)) {
            $this->available_elements = Config::get("app.expert_elements");
        }

        if (!is_string($fertilizer) || !is_array($additive) || !is_array($elements) || !is_array($additive_concentration)) {
            throw new Exception("Invalid input");
        }
        
        if (!is_array($element_units)) {
            throw new Exception("Invalid element_units");
        }
        
        if (!is_array($additive_units)) {
            throw new Exception("Invalid additive_units");
        }
        
        if (!isset($this->calculator->getFertilizers()[$fertilizer]) && $fertilizer !== "") {
            throw new Exception("Invalid fertilizer");
        }
        if (Config::get("app.models.$target_model", false) === false) {
            throw new Exception("Invalid target model");
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
            "chloride",
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
            if ($additive_units[$element] === "mg") {
                $additive_concentration[$element] = 100.0;
            }
        }

        if (!is_bool($support_dilution)) {
            $support_dilution = match (strtolower($support_dilution)) {
                "true", "on", "yes", "1" => true,
                default => false,
            };
        }

        $this->calculator->setDilutionSupport($support_dilution);

        $this->fertilizer = $fertilizer;
        $this->target_model = $target_model;
        $this->additive = $additive;
        $this->ratio = $ratio;
        $this->density = $density;
        $this->volume = $volume;
        $this->support_dilution = $support_dilution;
        $this->target_offset = $target_offset;
        $this->region = $region;
        $this->additive_concentration = $additive_concentration;
        $this->additive_units = $additive_units;

        $this->elements = $this->convertElements(array_merge($this->elements, $elements), array_merge($this->element_units, $element_units));

        if (($_GET["expert"] ?? null)) {

            if (count($target_calcium) !== count($target_magnesium) || count($target_calcium) === 0) {
                throw new Exception("Invalid input");
            }

            if (!is_array($additive_elements["calcium"] ?? null) || !is_array($additive_elements["magnesium"] ?? null)) {
                throw new Exception("Invalid input");
            }

            if (!is_array($additive_elements["calcium"]["calcium"] ?? null) || !is_array($additive_elements["magnesium"]["magnesium"] ?? null)) {
                throw new Exception("Invalid input");
            }

            foreach ($additive_elements as $element => $_additive_elements) {
                foreach ($_additive_elements as $additive => $value) {
                    if (!is_array($value)) {
                        throw new Exception("Invalid input");
                    }
                    if ((!isset($value["CaO"]) || !is_numeric($value["CaO"])) && (!isset($value["MgO"]) || !is_numeric($value["MgO"]))) {
                        throw new Exception("Invalid input");
                    }
                    foreach ($value as $key => $val) {
                        $value[$key] = (float)$val;
                    }
                    if (array_sum($value) === 0) {
                        throw new Exception("Invalid input");
                    }
                    $additive_elements[$element][$additive] = $value;
                }
            }

            $modelTargets = Config::get("app.models.".$target_model, []);
            foreach ($modelTargets as $week => $model) {
                if (!isset($target_calcium[$week]) || !isset($target_magnesium[$week])) {
                    throw new Exception("Invalid input");
                }
                $target_calcium[$week] = (float)$target_calcium[$week];
                $target_magnesium[$week] = (float)$target_magnesium[$week];
                if ($target_calcium[$week] <= 0 && $target_magnesium[$week] <= 0) {
                    throw new Exception("Invalid input");
                }
                if ($target_calcium[$week] < 0) {
                    $target_calcium[$week] = 0;
                }
                if ($target_magnesium[$week] < 0) {
                    $target_magnesium[$week] = 0;
                }
            }

            if (!isset($fertilizer_elements["calcium"])) {
                $fertilizer_elements["calcium"] = [];
            }
            if (!isset($fertilizer_elements["magnesium"])) {
                $fertilizer_elements["magnesium"] = [];
            }

            if (array_sum($fertilizer_elements["calcium"]) + array_sum($fertilizer_elements["magnesium"]) <= 0.0) {
                $fertilizer_name = "";
            } else {
                $fertilizer_name = __("content.form.fertilizer.custom.label");
            }

            $custom_fertilizer = [
                "name"     => $fertilizer_name,
                "elements" => $fertilizer_elements,
                "density"  => 1,
            ];

            $custom_additives = [
                "calcium"   => [
                    "name"     => __("content.form.additive.calcium.label"),
                    "elements" => $additive_elements["calcium"],
                    "density"  => 1,
                ],
                "magnesium" => [
                    "name"     => __("content.form.additive.magnesium.label"),
                    "elements" => $additive_elements["magnesium"],
                    "density"  => 1,
                ],
            ];

            $targets = [];
            foreach ($modelTargets as $week => $model) {
                $targets[$week] = [
                    "weeks"    => $week,
                    "state"    => $model["state"],
                    "elements" => [
                        "calcium"   => $target_calcium[$week] ?? 0,
                        "magnesium" => $target_magnesium[$week] ?? 0,
                    ],
                ];
            }
            $this->validated = true;

            $this->additive_elements = $additive_elements;
            $this->fertilizer_elements = $fertilizer_elements;
            $this->target_calcium = $target_calcium;
            $this->target_magnesium = $target_magnesium;

            if ($fertilizer_name !== "") {
                $this->calculator->addFertilizer($fertilizer_name, $custom_fertilizer);
            }
            $this->calculator->setFertilizer($fertilizer_name);
            $this->calculator->addAdditive("calcium", "custom_calcium", $custom_additives["calcium"]);
            $this->calculator->addAdditive("magnesium", "custom_magnesium", $custom_additives["magnesium"]);
            $this->calculator->setAdditive(["calcium" => "custom_calcium", "magnesium" => "custom_magnesium"], $this->additive_concentration);

            $this->calculator->setRatio($this->ratio, 1.0);
            $this->calculator->setTargetModel($this->target_model);
            $this->calculator->setModel($targets);
            $this->calculator->setTargetOffset($this->target_offset / 100);

            try {
                $this->calculator->setWater(["elements" => $this->elements]);
            } catch (Exception $e) {
                // Handle the exception
            }
        } else {
            try {
                $this->additive_elements = $additive_elements;
                $this->fertilizer_elements = $fertilizer_elements;
                $this->target_calcium = $target_calcium;
                $this->target_magnesium = $target_magnesium;

                $this->calculator->setFertilizer($this->fertilizer);
                $this->calculator->setAdditive($this->additive, $this->additive_concentration);
                $this->calculator->setRatio($this->ratio, 1.0);
                $this->calculator->setWater(["elements" => $this->elements]);
                $this->calculator->setTargetOffset($this->target_offset / 100);
                $this->calculator->setTargetModel($this->target_model);
            } catch (Exception $e) {
                // Handle the exception
            }
            $this->validated = true;
        }
    }

    /**
     * Convert a list of given elements to mg/L
     * @param array $elements The elements to be converted
     * @param array $element_units The units of the elements
     * @return array
     */
    private function convertElements(array $elements, array $element_units): array {
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