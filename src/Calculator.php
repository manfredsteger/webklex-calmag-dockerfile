<?php
/*
* File: Calculator.php
* Category: -
* Author: M.Goldenbaum
* Created: 05.11.24 18:19
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\CalMag;


use Webklex\CalMag\Enums\GrowState;

/**
 * Calculator class for managing and calculating nutrient ratios in hydroponics
 * Handles water, fertilizer, and additive calculations for calcium and magnesium
 */
class Calculator {
    protected array $models = [];
    protected $target_model = "linear";

    protected array $additives = [];

    protected array $ratios = [
        "calcium"   => 3.5,
        "magnesium" => 1.0,
    ];

    protected array $water = [
        "calcium"   => 0.001, // mg/L
        "magnesium" => 0.001, // mg/L
    ];

    protected array $fertilizers = [];

    protected string $fertilizer = "";
    protected array $additive = [
        "calcium"   => "",
        "magnesium" => "",
    ];

    protected bool $dilution_support = true;

    /**
     * Calculator constructor.
     * @param array $water The water configuration containing element concentrations
     * @param string $fertilizer The name of the fertilizer to use (default: "")
     * @param array $additive The additives configuration (default: [])
     * @param float $ratio The calcium to magnesium ratio (default: 3.5)
     * @param string $target_model The calculation model to use (default: "linear")
     * @throws \InvalidArgumentException If water values are missing
     */
    public function __construct(array $water, string $fertilizer = "", array $additive = [], float $ratio = 3.5, string $target_model = "linear") {
        if (!isset($water["elements"]["calcium"]) || !isset($water["elements"]["magnesium"])) {
            throw new \InvalidArgumentException("Water needs to have calcium and magnesium values");
        }
        $this->additives = Config::get("additives", []);

        foreach (Config::get("fertilizers", []) as $brand_key => $brand) {
            foreach ($brand["products"] as $product_key => $product) {
                if (!isset($product["elements"]["calcium"]) && !isset($product["elements"]["magnesium"])) {
                    continue;
                }
                $this->fertilizers[$brand["brand_name"] . " - " . $product["name"]] = [
                    ...$product,
                    "brand" => $brand["brand_name"],
                ];
            }
        }

        $this->setRatio($ratio, 1.0);
        $this->setFertilizer($fertilizer);
        $this->setAdditive($additive);

        $this->boot();
        $this->setTargetModel($target_model);
        $this->setWater($water);
    }

    /**
     * Initialize the calculator by processing fertilizers and additives
     * @return void
     */
    private function boot(): void {
        foreach ($this->fertilizers as $index => $fertilizer) {
            $elements = $this->summarizeElements($fertilizer["elements"]);
            $this->fertilizers[$index]["ratio"] = $elements['calcium'] / $elements['magnesium'];
            foreach ($this->fertilizers[$index]["elements"] as $component => $value) {
                if (is_array($value)) {
                    foreach ($value as $sub_element => $sub_value) {
                        $this->fertilizers[$index]["elements"][$component][$sub_element] = $sub_value * ($fertilizer["density"] ?? 1.0);
                    }
                } else {
                    $this->fertilizers[$index]["elements"][$component] = $value * ($fertilizer["density"] ?? 1.0);
                }
            }
        }
        foreach ($this->additives as $element => $additives) {
            foreach ($additives as $index => $additive) {
                $this->additives[$element][$index] = $this->calculateRealAdditiveConcentrations($additive);
            }
        }
    }

    /**
     * Calculate the actual concentrations of additives based on their properties
     * @param array $additive The additive configuration
     * @return array The processed additive with real concentrations
     */
    protected function calculateRealAdditiveConcentrations(array $additive): array {
        $additive['real'] = [];
        if (!isset($additive["elements"])) {
            $additive["elements"] = [];
        }
        foreach ($this->summarizeElements($additive['elements']) as $component => $value) {
            $additive['real'][$component] = (($value * 10) * ($additive['concentration'] / 100)) * ($additive["density"] ?? 1.0);
            if (!isset($additive["elements"][$component])) {
                $additive["elements"][$component] = 0;
            }
        }
        return $additive;
    }

    /**
     * Validate and normalize target values for calculations
     * @param array $target The target configuration
     * @return array The validated target configuration
     */
    protected function validateTarget(array $target): array {
        $elements = $target['elements'] ?? [
            "calcium"   => 0.001,
            "magnesium" => 0.001,
        ];

        if (!isset($elements['calcium']) || $elements['calcium'] <= 0) {
            $elements['calcium'] = $elements['magnesium'] * $this->ratios['calcium'];
        } elseif (!isset($elements['magnesium']) || $elements['magnesium'] <= 0) {
            $elements['magnesium'] = $elements['calcium'] / $this->ratios['calcium'];
        }
        $target['elements'] = $elements;
        $target['weeks'] = intval($target['weeks'] ?? 1);
        return $target;
    }

    /**
     * Calculate the nutrient ratios and required additions
     * @return array The calculation results including deficiency, results and table data
     */
    public function calculate(): array {
        $deficiency = $this->getDeficiencyRatio();
        $results = $this->getAppliedFertilizer();
        $table = $this->generateResultTable();

        return [
            "deficiency" => $deficiency,
            "results"    => $results,
            "table"      => $table,
        ];
    }

    /**
     * Generate a detailed results table for all calculation models
     * @return array The results table containing all calculation data
     */
    public function generateResultTable(): array {
        $weeks = [];
        foreach ($this->models as $week => $target) {
            $target_elements = $target["elements"];
            if (!isset($target_elements["magnesium"]) || $target_elements["magnesium"] <= 0) {
                $target_elements["magnesium"] = $target_elements["calcium"] / $this->ratios["calcium"];
            } elseif (!isset($target_elements["calcium"]) || $target_elements["calcium"] <= 0) {
                $target_elements["calcium"] = $target_elements["magnesium"] * $this->ratios["calcium"];
            }
            $_result = $this->calculateFertilizer([
                                                      ...$target,
                                                      "elements" => $target_elements,
                                                  ]);
            $weeks[$week] = [
                "result"          => $_result,
                "week"            => $week,
                "state"           => $target["state"],
                "target_elements" => $target_elements,
            ];
        }


        $ca_additive = $this->additives["calcium"][$this->additive["calcium"] ?? ""] ?? [];
        $mg_additive = $this->additives["magnesium"][$this->additive["magnesium"] ?? ""] ?? [];

        $table = [
            "models"      => $this->models,
            "fertilizer"  => [
                "name" => $this->fertilizer,
                "rows" => [],
            ],
            "ca_additive" => [
                "name"          => $this->additive["calcium"] ?? "",
                "concentration" => $ca_additive["concentration"] ?? 0,
                "rows"          => [],
            ],
            "mg_additive" => [
                "name"          => $this->additive["magnesium"] ?? "",
                "concentration" => $mg_additive["concentration"] ?? 0,
                "rows"          => [],
            ],
            "elements"    => [],
            "water"       => [],
            "ratio"       => [],
            "target"      => [],
            "missing"     => [],
            "suggested"   => [],
        ];

        foreach ($weeks as $week) {
            $table["fertilizer"]["rows"][$week["week"]] = $week["result"]["fertilizer"]["ml"] ?? 0;
            $table["ca_additive"]["rows"][$week["week"]] = $week["result"]["additive"]["calcium"] ?? [];
            $table["mg_additive"]["rows"][$week["week"]] = $week["result"]["additive"]["magnesium"] ?? [];
            $table["elements"][$week["week"]] = $week["result"]["elements"];
            $table["water"][$week["week"]] = [
                "water"    => $week["result"]["water"],
                "dilution" => $week["result"]["dilution"],
            ];
            $table["ratio"][$week["week"]] = $week["result"]["ratio"];
            $table["target"][$week["week"]] = [
                ...$week["result"]["target"],
                "state"    => $week["state"],
                "elements" => $week["target_elements"],
            ];
            $table["missing"][$week["week"]] = $week["result"]["missing"];
            $table["suggested"][$week["week"]] = $week["result"]["suggested_additive"];
        }

        return $table;
    }

    /**
     * Get the applied fertilizer results for all growth states
     * @return array An array containing the calculated fertilizer results per growth state
     */
    public function getAppliedFertilizer(): array {
        $targets = [];
        foreach ($this->models as $week => $target) {
            if (!isset($targets[$target["state"]])) {
                $targets[$target["state"]] = [
                    ...$target,
                    "weeks" => 1,
                ];
            } else {
                foreach ($target["elements"] as $component => $value) {
                    if (is_array($value)) {
                        foreach ($value as $sub_element => $sub_value) {
                            $targets[$target["state"]]["elements"][$component][$sub_element] += $sub_value;
                        }
                    } else {
                        $targets[$target["state"]]["elements"][$component] += $value;
                    }
                }
                $targets[$target["state"]]["weeks"]++;
            }
        }

        $result = [];
        foreach ($targets as $state => $target) {
            foreach ($target["elements"] as $component => $value) {
                if (is_array($value)) {
                    foreach ($value as $sub_element => $sub_value) {
                        $target["elements"][$component][$sub_element] = $sub_value / $target["weeks"];
                    }
                } else {
                    $target["elements"][$component] = $value / $target["weeks"];
                }
            }
            $result[$state] = $this->calculateFertilizer($target);
        }
        return $result;
    }

    /**
     * Summarize a given element array
     * @param array $elements
     * @return array
     */
    public function summarizeElements(array $elements): array {
        $result = [
            "calcium"   => 0,
            "magnesium" => 0,
        ];
        foreach ($elements as $component => $value) {
            if (!isset($result[$component])) {
                $result[$component] = 0;
            }
            if (is_array($value)) {
                foreach ($value as $sub_element => $sub_value) {
                    $result[$component] += match ($sub_element) {
                        "CaO" => $sub_value * 0.7143,
                        "MgO" => $sub_value * 0.6032,
                        default => $sub_value,
                    };
                }
            } else {
                $result[$component] += $value;
            }
        }
        return $result;
    }

    /**
     * Calculate the fertilizer needed to reach the target
     * @param array $target The target elements
     * @return array
     */
    public function calculateFertilizer(array $target): array {
        $result = [];
        $fertilizer = $this->fertilizers[$this->fertilizer] ?? [
            "elements" => [],
        ];
        $elements = $this->summarizeElements($this->water["elements"]);
        $target = $this->validateTarget($target);
        $dilution = 1.0;

        foreach ($target["elements"] as $component => $target_value) {
            if (!isset($elements[$component])) {
                $elements[$component] = 0;
            }
            if ($elements[$component] > $target_value && $this->dilution_support) {
                $stock = $target_value / $elements[$component];

                foreach ($elements as $element => $element_value) {
                    $elements[$element] = $element_value * $stock;
                }
                $dilution = $dilution * $stock;
            }
        }

        $fertilizer_nanoliter = 0;
        $fertilizer_elements = $this->summarizeElements($fertilizer["elements"]);
        if ($fertilizer_elements['calcium'] > 0 && $fertilizer_elements['magnesium'] > 0) {
            while (
                ($elements['calcium'] < $target["elements"]['calcium']) &&
                ($elements['calcium'] - $target["elements"]['calcium'] < 0) &&
                ($elements['magnesium'] < $target["elements"]['magnesium']) &&
                ($elements['magnesium'] - $target["elements"]['magnesium'] < 0)
            ) {
                foreach ($fertilizer_elements as $component => $value) {
                    if (!isset($elements[$component])) {
                        $elements[$component] = 0;
                    }
                    $elements[$component] += ($value * 10) / 100; // mg/ml
                }
                $fertilizer_nanoliter++;
            }
        }

        $result['fertilizer'] = [
            "ml"   => $fertilizer_nanoliter / 100,
            "name" => $this->fertilizer,
        ];

        if (!isset($elements["calcium"]) || $elements["calcium"] <= 0) {
            $elements["calcium"] = 0.001;
        }
        if (!isset($elements["magnesium"]) || $elements["magnesium"] <= 0) {
            $elements["magnesium"] = 0.001;
        }

        foreach ($this->additive as $element => $name) {
            $additive = $this->additives[$element][$name] ?? null;
            if ($additive === null) {
                $result['additive'][$element] = [
                    "ml"            => 0,
                    "mg"            => 0,
                    "name"          => $name,
                    "concentration" => 100,
                ];
                continue;
            }

            $additive_nanoliter = 0;
            while ((
                        ($elements['magnesium'] < $target["elements"]['magnesium']) &&
                        ($elements['magnesium'] - $target["elements"]['magnesium'] < 0)
                    )
                ) {
                if (!isset($additive['real']["magnesium"]) || $additive['real']["magnesium"] <= ($additive['real']["calcium"] ?? 0)) {
                    break;
                }
                foreach ($additive['real'] as $component => $value) {
                    if (!isset($elements[$component])) {
                        $elements[$component] = 0;
                    }
                    $elements[$component] += $value / 100;
                }
                $additive_nanoliter++;
            }
            while (
                    (
                        ($elements['calcium'] < $target["elements"]['calcium']) &&
                        ($elements['calcium'] - $target["elements"]['calcium'] < 0)
                    )
                ) {
                if (!isset($additive['real']["calcium"]) || $additive['real']["calcium"] <= ($additive['real']["magnesium"] ?? 0)) {
                    break;
                }
                foreach ($additive['real'] as $component => $value) {
                    if (!isset($elements[$component])) {
                        $elements[$component] = 0;
                    }
                    $elements[$component] += $value / 100;
                }
                $additive_nanoliter++;
            }

            if ($additive_nanoliter > 10) {
                if (
                    ($elements['calcium'] / $elements['magnesium'] < $this->ratios['calcium'] && ($target["elements"]['calcium'] < $elements['calcium'] || $target["elements"]['calcium'] == 0)) ||
                    ($elements['calcium'] / $elements['magnesium'] < $this->ratios['calcium'] && ($target["elements"]['magnesium'] < $elements['magnesium'] || $target["elements"]['magnesium'] == 0))
                ) {
                    foreach ($additive['real'] as $component => $value) {
                        if ($value > 0) {
                            $elements[$component] -= $value / 100;
                        }
                    }
                    $additive_nanoliter -= 1;
                }
            } elseif ($additive_nanoliter < 10 && $additive_nanoliter > 0) {
                do {
                    foreach ($additive['real'] as $component => $value) {
                        if ($value > 0) {
                            $elements[$component] -= $value / 100;
                        }
                    }
                    $additive_nanoliter -= 1;
                } while ($additive_nanoliter > 0);
            }

            // How many mg of additive are dissolved based on the additive concentration
            $additive_grams = ($additive_nanoliter / 100) * ($additive['concentration'] / 100);

            $result['additive'][$element] = [
                "ml"            => $additive_nanoliter / 100,
                "mg"            => $additive_grams * 1000,
                "name"          => $name,
                "concentration" => $additive['concentration'],
            ];
        }

        $result["target"] = $target;

        $result['missing'] = [
            "calcium"   => $target["elements"]['calcium'] - $this->water["elements"]['calcium'],
            "magnesium" => $target["elements"]['magnesium'] - $this->water["elements"]['magnesium'],
        ];

        $result['suggested_additive'] = $this->getSuggestedAdditives($result['missing']);

        $result["ratio"] = $elements['calcium'] / $elements['magnesium'];
        $result["elements"] = $elements;
        $result["dilution"] = $dilution; // tap water
        $result["water"] = 1.0 - $dilution; // osmosis water

        // Check if the target is reached (within 3% deviation)
        $tolerance = 0.05;
        $target_reached = abs($result["elements"]["calcium"] - $result["target"]["elements"]["calcium"]) <= ($result["target"]["elements"]["calcium"] * $tolerance) &&
            abs($result["elements"]["magnesium"] - $result["target"]["elements"]["magnesium"]) <= ($result["target"]["elements"]["magnesium"] * $tolerance);

        if (!$target_reached && $dilution > 0.1 && $this->fertilizer !== "" && $this->dilution_support) {
            $elements = $this->summarizeElements($this->water["elements"]);

            // dilute the water until the target can be reached (within 3% deviation) by adding a fertilizer or the water is completely diluted (10%)
            $ca_water_ratio = $this->water["elements"]['calcium'] / $this->water["elements"]['magnesium'];
            $ca_fertilizer_ratio = $fertilizer_elements['calcium'] / $fertilizer_elements['magnesium'];
            if ($ca_water_ratio > $ca_fertilizer_ratio) {
                $runs = 5000;
                do {
                    $_ration = $elements['calcium'] / $elements['magnesium'];
                    if ($_ration > $this->ratios['calcium'] + ($this->ratios['calcium'] * $tolerance) || $_ration < $this->ratios['calcium'] - ($this->ratios['calcium'] * $tolerance)) {
                        foreach ($fertilizer_elements as $component => $value) {
                            if (!isset($elements[$component])) {
                                $elements[$component] = 0;
                            }
                            $elements[$component] += ($value * 10) / 100; // mg/ml
                        }
                    }
                } while (
                    (
                        ($_ration > $this->ratios['calcium'] + ($this->ratios['calcium'] * $tolerance)) ||
                        ($_ration < $this->ratios['calcium'] - ($this->ratios['calcium'] * $tolerance))
                    ) && $runs-- >= 0);

                $ca_factor = $target["elements"]['calcium'] / $elements['calcium'];
                $mg_factor = $target["elements"]['magnesium'] / $elements['magnesium'];

                $dilution = min($ca_factor, $mg_factor);

                if ($dilution <= 1.0 && $dilution > 0) {
                    $result["dilution"] = $dilution;
                    $result["water"] = 1.0 - $dilution;

                    $fertilizer = $this->fertilizers[$this->fertilizer] ?? [
                        "elements" => [],
                    ];
                    $elements = $this->summarizeElements($this->water["elements"]);

                    foreach ($elements as $element => $element_value) {
                        $elements[$element] = $element_value * $dilution;
                    }

                    $fertilizer_nanoliter = 0;
                    $fertilizer_elements = $this->summarizeElements($fertilizer["elements"]);
                    if ($fertilizer_elements['calcium'] > 0 && $fertilizer_elements['magnesium'] > 0) {
                        while (
                            ($elements['calcium'] < $target["elements"]['calcium']) &&
                            ($elements['calcium'] - $target["elements"]['calcium'] < 0) &&
                            ($elements['magnesium'] < $target["elements"]['magnesium']) &&
                            ($elements['magnesium'] - $target["elements"]['magnesium'] < 0)
                        ) {
                            foreach ($fertilizer_elements as $component => $value) {
                                if (!isset($elements[$component])) {
                                    $elements[$component] = 0;
                                }
                                $elements[$component] += ($value * 10) / 100; // mg/ml
                            }
                            $fertilizer_nanoliter++;
                        }
                    }
                    $result["ratio"] = $elements['calcium'] / $elements['magnesium'];
                    $result["elements"] = $elements;

                    $result['fertilizer'] = [
                        "ml"   => $fertilizer_nanoliter / 100,
                        "name" => $this->fertilizer,
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * Get the suggested additives
     * @param array $missing [element => missing]
     *
     * @return array
     */
    public function getSuggestedAdditives(array $missing): array {
        $suggested_additive = [];
        foreach ($this->additives as $element => $additives) {
            $_missing = $missing[$element] ?? 0;
            if ($_missing <= 0) {
                continue;
            }
            $_additive = $this->additives[$element][$this->additive[$element] ?? ""] ?? null;
            if ($_additive === null) {
                continue;
            }
            $_elements = $this->calculateRealAdditiveConcentrations([
                                                                        "elements"      => $_additive['elements'],
                                                                        "concentration" => 100,
                                                                    ])["real"];
            if (($_elements[$element] ?? 0) <= 0) {
                continue;
            }
            // how high should the concentration be, if 1x Solution contains the missing amount of calcium
            $_delta = $_missing / $_elements[$element];
            $_concentration = $_delta * 100;
            $_ml = 1.0;
            if ($_concentration > 100) {
                $_ml = $_concentration / 100;
                $_concentration = 100;
            }

            $suggested_additive[$element] = [
                "missing"  => $_missing,
                "additive" => $this->additive[$element],
                "ml"       => $_ml,
                ...$this->calculateRealAdditiveConcentrations([
                                                                  "elements"      => $_additive['elements'],
                                                                  "concentration" => $_concentration,
                                                              ])
            ];
        }
        return $suggested_additive;
    }

    /**
     * Get the deficiency ratios
     * @return array|float[]
     */
    public function getDeficiencyRatio(): array {
        if ($this->water["elements"]['calcium'] > $this->water["elements"]['magnesium']) {
            return [
                "calcium"   => $this->water["elements"]['calcium'] / ($this->water["elements"]['magnesium'] ?? 1),
                "magnesium" => 1.0,
            ];
        } else if ($this->water["elements"]['calcium'] < $this->water["elements"]['magnesium']) {
            return [
                "calcium"   => 1.0,
                "magnesium" => $this->water["elements"]['magnesium'] / ($this->water["elements"]['calcium'] ?? 1),
            ];
        }
        return [
            "calcium"   => 1.0,
            "magnesium" => 1.0,
        ];
    }

    /**
     * Set the fertilizer
     * @param string $fertilizer The fertilizer name
     * @return void
     */
    public function setFertilizer(string $fertilizer): void {
        if (!isset($this->fertilizers[$fertilizer]) && $fertilizer !== "") {
            throw new \InvalidArgumentException("Fertilizer not found");
        }
        $this->fertilizer = $fertilizer;
    }

    /**
     * Set the additive
     * @param array $additives [element => additive]
     * @param array $concentrations [element => concentration]
     *
     * @return void
     */
    public function setAdditive(array $additives, array $concentrations = []): void {
        foreach ($additives as $element => $additive) {
            if (!isset($this->additives[$element][$additive]) && $additive !== "") {
                throw new \InvalidArgumentException("Additive not found");
            }
        }
        $this->additive = $additives;
        foreach ($concentrations as $element => $concentration) {
            if (isset($additives[$element])) {
                $this->additives[$element][$additives[$element]]['concentration'] = $concentration;
                $this->additives[$element][$additives[$element]] = $this->calculateRealAdditiveConcentrations($this->additives[$element][$additives[$element]] ?? []);
            }
        }
    }

    /**
     * Set the water values
     * @param array $water The water values
     * @return void
     */
    public function setWater(array $water): void {
        $fertilizer = $this->fertilizers[$this->fertilizer] ?? [
            "elements" => [],
        ];

        if (isset($water["elements"]["sulphate"])) {
            $water["elements"]["sulfur"] = ($water["elements"]["sulfur"] ?? 0) + $water["elements"]["sulphate"] * 0.334;
        }
        if (isset($elements["chloride"])) {
            $water["elements"]["chlorine"] = ($water["elements"]["chlorine"] ?? 0) + $elements["chloride"] * 0.5256;
        }
        if (isset($water["elements"]["nitrate"])) {
            $water["elements"]["nitrogen"] = ($water["elements"]["nitrogen"] ?? 0) + ($water["elements"]["nitrate"] * 0.226);
        }
        if (isset($water["elements"]["nitrite"])) {
            $water["elements"]["nitrogen"] = ($water["elements"]["nitrogen"] ?? 0) + ($water["elements"]["nitrite"] * 0.304);
        }
        if (!isset($water["elements"]["calcium"]) || $water["elements"]["calcium"] <= 0) {
            $water["elements"]["calcium"] = 0.001;
        }
        if (!isset($water["elements"]["magnesium"]) || $water["elements"]["magnesium"] <= 0) {
            $water["elements"]["magnesium"] = 0.001;
        }

        $this->water = [
            ...$water,
            "elements" => [],
        ];

        if (count($fertilizer["elements"]) > 0) {
            foreach ($fertilizer["elements"] as $component => $value) {
                if (!isset($water["elements"][$component])) {
                    $this->water["elements"][$component] = 0;
                }
                $this->water["elements"][$component] = $water["elements"][$component] ?? 0.0;
            }
        } else {
            $this->water["elements"] = $water["elements"];
        }


        foreach ($this->additive as $element => $name) {
            if ($name === "") {
                continue;
            }
            $additive = $this->additives[$element][$name];
            foreach ($additive["real"] as $component => $value) {
                if (!isset($water["elements"][$component])) {
                    $this->water["elements"][$component] = 0;
                }
                $this->water["elements"][$component] = $water["elements"][$component] ?? 0.0;
            }
        }
    }

    /**
     * Set the target offset
     * @param float $offset The offset
     *
     * @return void
     */
    public function setTargetOffset(float $offset): void {
        foreach ($this->models as $index => $target) {
            $target["elements"]["calcium"] = $target["elements"]["calcium"] ?? 0.0;
            $target["elements"]["magnesium"] = $target["elements"]["magnesium"] ?? 0.0;
            $this->models[$index] = [
                ...$target,
                "elements" => [
                    "calcium"   => $target["elements"]["calcium"] + ($target["elements"]["calcium"] * $offset),
                    "magnesium" => $target["elements"]["magnesium"] + ($target["elements"]["magnesium"] * $offset),
                ],
            ];
        }
    }

    /**
     * Set the calcium and magnesium ratio
     * @param float $calcium The calcium ratio
     * @param float $magnesium The magnesium ratio
     *
     * @return void
     */
    public function setRatio(float $calcium, float $magnesium): void {
        $this->ratios = [
            "calcium"   => $calcium,
            "magnesium" => $magnesium,
        ];
        foreach ($this->models as $index => $target) {
            $this->models[$index] = $this->validateTarget($target);
        }
    }

    /**
     * Get the currently loaded water values
     * @return array
     */
    public function getWater(): array {
        return $this->water;
    }

    /**
     * Get the components for the currently selected fertilizer
     * @param float $ml The amount of fertilizer in ml
     *
     * @return array
     */
    public function getFertilizerComponents(float $ml): array {
        $fertilizer = $this->fertilizers[$this->fertilizer] ?? [
            "elements" => [],
        ];
        $result = [];

        $elements = $this->summarizeElements($fertilizer["elements"]);

        foreach ($elements as $component => $value) {
            $result[$component] = ($value * 10) * $ml;
        }

        return $result;
    }

    /**
     * Get the additive components for a specific element
     * @param string $element The element to get the additive components for
     * @param float $ml The amount of additive in ml
     * @return array The additive components and their values
     */
    public function getAdditiveComponents(string $element, float $ml): array {
        $additive = $this->additives[$element][$this->additive[$element] ?? ""] ?? null;
        $result = [];
        if ($additive === null) {
            return $result;
        }

        foreach ($additive['real'] as $component => $value) {
            $result[$component] = $value * $ml;
        }

        return $result;
    }

    /**
     * Get the currently configured additive settings
     * @return array The current additive configuration
     */
    public function getAdditive(): array {
        return $this->additive;
    }

    /**
     * Get the currently selected fertilizer name
     * @return string The fertilizer name
     */
    public function getFertilizer(): string {
        return $this->fertilizer;
    }

    /**
     * Set the target values for a specific week
     * @param int $week The week number
     * @param array $target The target configuration
     * @return void
     */
    public function setTarget(int $week, array $target): void {
        $this->models[$week] = $this->validateTarget($target);
    }

    /**
     * Get all configured calculation models
     * @return array The calculation models
     */
    public function getModels(): array {
        return $this->models;
    }

    /**
     * Get all available fertilizers
     * @return array The available fertilizers
     */
    public function getFertilizers(): array {
        return $this->fertilizers;
    }

    /**
     * Get all available additives
     * @return array The available additives
     */
    public function getAdditives(): array {
        $additives = [];
        foreach ($this->additives as $element => $additive_group) {
            $additives[$element] = [];
            foreach ($additive_group as $index => $additive) {
                if ($index != "") {
                    $additives[$element][$index] = $additive;
                }
            }
        }
        return $additives;
    }

    /**
     * Get all available elements and their properties
     * @return array The available elements
     */
    public function getElements(): array {
        $elements = [];
        foreach ($this->water["elements"] as $element => $value) {
            $elements[] = $element;
        }
        foreach ($this->fertilizers as $fertilizer) {
            foreach ($fertilizer["elements"] as $element => $value) {
                $elements[] = $element;
            }
        }
        foreach ($this->additives as $element => $additives) {
            foreach ($additives as $additive) {
                foreach ($additive["elements"] as $element => $value) {
                    $elements[] = $element;
                }
            }

        }
        $elements = array_unique($elements);
        sort($elements);
        return $elements;
    }

    /**
     * Get the ratio for a specific element
     * @param string $element The element name
     * @return float The element ratio
     */
    public function getRatio(string $element): float {
        return $this->ratios[$element];
    }

    /**
     * Add a new fertilizer to the available fertilizers
     * @param string $name The fertilizer name
     * @param array $fertilizer The fertilizer configuration
     * @return void
     */
    public function addFertilizer(string $name, array $fertilizer): void {
        $this->fertilizers[$name] = $fertilizer;
    }

    /**
     * Add a new additive for a specific element
     * @param string $element The element name
     * @param string $name The additive name
     * @param array $additive The additive configuration
     * @return void
     */
    public function addAdditive(string $element, string $name, array $additive): void {
        $this->additives[$element][$name] = $additive;
    }

    /**
     * Set the calculation models
     * @param array $models The calculation models
     * @return void
     */
    public function setModels(array $models): void {
        foreach ($models as $index => $target) {
            $this->models[$index] = $this->validateTarget($target);
        }
    }

    /**
     * Enable or disable dilution support
     * @param bool $dilution_support Whether to enable dilution support
     * @return void
     */
    public function setDilutionSupport(bool $dilution_support): void {
        $this->dilution_support = $dilution_support;
    }

    /**
     * Set the target calculation model
     * @param string $target_model The target model name
     * @return void
     */
    public function setTargetModel(string $target_model) {
        $this->target_model = $target_model;
        $this->setModel(Config::get("app.models.$target_model", []));
    }

    /**
     * Set the calculation model
     * @param array $models The model configuration
     * @return void
     */
    public function setModel(array $models): void {
        $this->models = $models;
    }
}
