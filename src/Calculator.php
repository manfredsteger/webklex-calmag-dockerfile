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

class Calculator {
    protected array $targets = [];

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

    /**
     * Calculator constructor.
     * @param array $water
     * @param string $fertilizer
     * @param array $additive
     * @param float $ratio
     */
    public function __construct(array $water, string $fertilizer = "", array $additive = [], float $ratio = 3.5) {
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

        $this->setWater($water);
    }

    private function boot(): void {
        foreach ($this->fertilizers as $index => $fertilizer) {
            $elements = $this->summarizeElements($fertilizer["elements"]);
            $this->fertilizers[$index]["ratio"] = $elements['calcium'] / $elements['magnesium'];
            foreach ($this->fertilizers[$index]["elements"] as $component => $value) {
                if (is_array($value)) {
                    foreach ($value as $sub_element => $sub_value) {
                        $this->fertilizers[$index][$component][$sub_element] = $sub_value * ($fertilizer["density"] ?? 1.0);
                    }
                } else {
                    $this->fertilizers[$index][$component] = $value * ($fertilizer["density"] ?? 1.0);
                }
            }
        }
        foreach ($this->additives as $element => $additives) {
            foreach ($additives as $index => $additive) {
                $this->additives[$element][$index] = $this->calculateRealAdditiveConcentrations($additive);
            }
        }
        foreach (Config::get("app.targets", []) as $index => $target) {
            $this->targets[$index] = $this->validateTarget($target);
        }
    }

    protected function calculateRealAdditiveConcentrations(array $additive): array {
        $additive['real'] = [];
        foreach ($this->summarizeElements($additive['elements'] ?? []) as $component => $value) {
            $additive['real'][$component] = (($value * 10) * ($additive['concentration'] / 100)) * ($additive["density"] ?? 1.0);
        }
        return $additive;
    }

    protected function validateTarget(array $target): array {
        $elements = $target['elements'] ?? [
            "calcium"   => 0.001,
            "magnesium" => 0.001,
        ];

        if (!isset($elements['calcium'])) {
            $elements['calcium'] = $elements['magnesium'] * $this->ratios['calcium'];
        } elseif (!isset($elements['magnesium']) || $elements['magnesium'] <= 0) {
            $elements['magnesium'] = $elements['calcium'] / $this->ratios['calcium'];
        }
        $target['elements'] = $elements;
        return $target;
    }

    public function calculate(): array {
        $deficiency = $this->getDeficiencyRatio();
        $results = $this->getAppliedFertilizer();
        $table = $this->generateResultTable($results);
        return [
            "deficiency" => $deficiency,
            "results"    => $results,
            "table"      => $table,
        ];
    }

    public function generateResultTable(array $results): array {
        $start_elements = $this->targets[GrowState::Propagation->value]["elements"] ?? [
            "calcium" => 40,
        ];
        $weeks = [];
        $week_num = 0;
        foreach ($this->targets as $state => $target) {
            $end_elements = $target["elements"];
            $delta_elements = [];
            foreach ($end_elements as $component => $value) {
                $delta_elements[$component] = $value - ($start_elements[$component] ?? 0);
            }
            for ($i = 0; $i < $target["weeks"]; $i++) {
                $target_elements = [];
                foreach ($end_elements as $component => $value) {
                    $target_elements[$component] = $start_elements[$component] + ($delta_elements[$component] / $target["weeks"]) * ($i + 1);
                }
                if (isset($target_elements["calcium"])) {
                    $target_elements["magnesium"] = $target_elements["calcium"] / $this->ratios["calcium"];
                }
                $_result = $this->calculateFertilizer([
                                                          ...$target,
                                                          "elements" => $target_elements,
                                                      ]);
                $weeks[$week_num++] = [
                    "result" => $_result,
                    "week"   => $week_num,
                    "state"  => $state,
                    "target_elements" => $target_elements,
                ];
            }

            $start_elements = $end_elements;
        }


        $ca_additive = $this->additives["calcium"][$this->additive["calcium"]] ?? [];
        $mg_additive = $this->additives["magnesium"][$this->additive["magnesium"]] ?? [];

        $table = [
            "targets"    => $this->targets,
            "fertilizer"  => [
                "name" => $this->fertilizer,
                "rows" => [],
            ],
            "ca_additive" => [
                "name" => $this->additive["calcium"],
                "concentration" => $ca_additive["concentration"] ?? 0,
                "rows" => [],
            ],
            "mg_additive" => [
                "name" => $this->additive["magnesium"],
                "concentration" => $mg_additive["concentration"] ?? 0,
                "rows" => [],
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
                "state" => $week["state"],
                "elements" => $week["target_elements"],
            ];
            $table["missing"][$week["week"]] = $week["result"]["missing"];
            $table["suggested"][$week["week"]] = $week["result"]["suggested_additive"];
        }

        return $table;
    }

    public function getAppliedFertilizer(): array {
        $result = [];
        foreach ($this->targets as $state => $target) {
            $result[$state] = $this->calculateFertilizer($target);
        }
        return $result;
    }

    protected function summarizeElements(array $elements): array {
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

    public function calculateFertilizer(array $target): array {
        $result = [];
        $fertilizer = $this->fertilizers[$this->fertilizer] ?? [
            "elements" => [],
        ];
        $elements = $this->summarizeElements($this->water["elements"]);
        $dilution = 1.0;

        foreach ($target["elements"] as $component => $target_value) {
            if (!isset($elements[$component])) {
                $elements[$component] = 0;
            }
            if ($elements[$component] > $target_value) {
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
            $additive = $this->additives[$element][$name];

            $additive_nanoliter = 0;
            while ($elements['calcium'] / $elements['magnesium'] > $this->ratios['calcium'] || (
                    ($elements['calcium'] < $target["elements"]['calcium']) &&
                    ($elements['calcium'] - $target["elements"]['calcium'] < 0) &&
                    ($elements['magnesium'] < $target["elements"]['magnesium']) &&
                    ($elements['magnesium'] - $target["elements"]['magnesium'] < 0)
                )) {
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
            while ($elements['calcium'] / $elements['magnesium'] < $this->ratios['calcium'] || (
                    ($elements['calcium'] < $target["elements"]['calcium']) &&
                    ($elements['calcium'] - $target["elements"]['calcium'] < 0) &&
                    ($elements['magnesium'] < $target["elements"]['magnesium']) &&
                    ($elements['magnesium'] - $target["elements"]['magnesium'] < 0)
                )) {
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
                if ($elements['calcium'] / $elements['magnesium'] < $this->ratios['calcium']) {
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
        $result["dilution"] = $dilution;
        $result["water"] = 1.0 - $dilution;

        // Check if the target is reached (within 3% deviation)
        $tolerance = 0.05;
        $target_reached = abs($result["elements"]["calcium"] - $result["target"]["elements"]["calcium"]) <= ($result["target"]["elements"]["calcium"] * $tolerance) &&
            abs($result["elements"]["magnesium"] - $result["target"]["elements"]["magnesium"]) <= ($result["target"]["elements"]["magnesium"] * $tolerance);

        if (!$target_reached && $dilution > 0.1 && $this->fertilizer !== "") {
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

        return $result;
    }

    private function getSuggestedAdditives(array $missing): array {
        $suggested_additive = [];
        foreach ($this->additives as $element => $additives) {
            $_missing = $missing[$element] ?? 0;
            if ($_missing <= 0) {
                continue;
            }
            $_additive = $this->additives[$element][$this->additive[$element]];
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

    public function setFertilizer(string $fertilizer): void {
        if (!isset($this->fertilizers[$fertilizer]) && $fertilizer !== "") {
            throw new \InvalidArgumentException("Fertilizer not found");
        }
        $this->fertilizer = $fertilizer;
    }

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

    public function setWater(array $water): void {
        $fertilizer = $this->fertilizers[$this->fertilizer] ?? [
            "elements" => [],
        ];

        if (isset($water["elements"]["sulphate"])) {
            $water["elements"]["sulfur"] = $water["elements"]["sulphate"] * 0.334;
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

        foreach ($fertilizer["elements"] as $component => $value) {
            if (!isset($water["elements"][$component])) {
                $this->water["elements"][$component] = 0;
            }
            $this->water["elements"][$component] = $water["elements"][$component];
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

    public function setRatio(float $calcium, float $magnesium): void {
        $this->ratios = [
            "calcium"   => $calcium,
            "magnesium" => $magnesium,
        ];
        foreach ($this->targets as $index => $target) {
            $this->targets[$index] = $this->validateTarget($target);
        }
    }

    public function getWater(): array {
        return $this->water;
    }

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

    public function getAdditiveComponents(string $element, float $ml): array {
        $additive = $this->additives[$element][$this->additive[$element]];
        $result = [];

        foreach ($additive['real'] as $component => $value) {
            $result[$component] = $value * $ml;
        }

        return $result;
    }

    public function getAdditive(): array {
        return $this->additive;
    }

    public function getFertilizer(): string {
        return $this->fertilizer;
    }

    public function setTarget(GrowState $state, array $target): void {
        $this->targets[$state->value] = $this->validateTarget($target);
    }

    public function getFertilizers(): array {
        return $this->fertilizers;
    }

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
        return array_unique($elements);
    }
}
