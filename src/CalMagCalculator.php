<?php
/*
* File: CalMagCalculator.php
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

class CalMagCalculator {
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
    protected string $additive = "";

    public function __construct(array  $water,
                                string $fertilizer = "",
                                string $additive = "",
                                float  $ratio = 3.5,
    ) {
        if(!isset($water["elements"]["calcium"]) || !isset($water["elements"]["magnesium"])) {
            throw new \InvalidArgumentException("Water needs to have calcium and magnesium values");
        }
        $this->additives = require __DIR__."/config/additives.php";
        $this->fertilizers = require __DIR__."/config/fertilizers.php";
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
        }
        foreach ($this->additives as $index => $additive) {
            $this->additives[$index] = $this->calculateRealAdditiveConcentrations($additive);
        }
        foreach($this->defaultTargets() as $index => $target) {
            $this->targets[$index] = $this->validateTarget($target);
        }
    }

    private function defaultTargets(): array {
        return [
            GrowState::Vegetation->value => [
                //"magnesium" => 26.67, // mg/L
                "calcium" => 80, // mg/L
            ],
            GrowState::Flower->value     => [
                //"magnesium" => 33.33, // mg/L
                "calcium" => 100, // mg/L
            ],
            GrowState::LateFlower->value => [
                //"magnesium" => 43.33, // mg/L
                "calcium" => 120, // mg/L
            ],
        ];
    }

    protected function calculateRealAdditiveConcentrations(array $additive): array {
        $additive['real'] = [];
        foreach($this->summarizeElements($additive['elements']) as $component => $value) {
            $additive['real'][$component] = ($value * 10) * ($additive['concentration'] / 100);
        }
        return $additive;
    }

    protected function validateTarget(array $target): array {
        if(!isset($target['calcium'])) {
            $target['calcium'] = $target['magnesium'] * $this->ratios['calcium'];
        }elseif(!isset($target['magnesium'])) {
            $target['magnesium'] = $target['calcium'] / $this->ratios['calcium'];
        }
        return $target;
    }

    public function calculate(): array {
        return [
            "deficiency" => $this->getDeficiencyRatio(),
            "results" => $this->getAppliedFertilizer(),
        ];
    }

    public function getAppliedFertilizer(): array {
        $result = [];
        foreach($this->targets as $state => $target) {
            $result[$state] = $this->calculateFertilizer($target);
        }
        return $result;
    }

    protected function summarizeElements(array $elements): array {
        $result = [];
        foreach($elements as $component => $value) {
            if(!isset($result[$component])) {
                $result[$component] = 0;
            }
            if(is_array($value)) {
                foreach($value as $sub_element => $sub_value) {
                    $result[$component] += match ($sub_element) {
                        "CaO" => $sub_value * 0.7143,
                        "MgO" => $sub_value * 0.6032,
                        default => $sub_value,
                    };
                }
            }else{
                $result[$component] += $value;
            }
        }
        return $result;
    }

    public function calculateFertilizer(array $target): array {
        $result = [];
        $fertilizer = $this->fertilizers[$this->fertilizer];
        $additive = $this->additives[$this->additive];
        $elements = $this->summarizeElements($this->water["elements"]);
        $dilution = 1.0;


        foreach($target as $component => $value) {
            if(!isset($elements[$component])) {
                $elements[$component] = 0;
            }
            if($elements[$component] > $value) {
                $stock = $value / $elements[$component];
                foreach ($elements as $element => $element_value) {
                    $elements[$element] = $element_value * $stock;
                }
                $dilution = $stock;
            }
        }

        $fertilizer_nanoliter = 0;
        while(($elements['calcium'] < $target['calcium']) && ($elements['calcium'] - $target['calcium'] < 0)) {
            foreach ($this->summarizeElements($fertilizer["elements"]) as $component => $value) {
                if(!isset($elements[$component])) {
                    $elements[$component] = 0;
                }
                $elements[$component] += ($value*10) / 100; // mg/ml
            }
            $fertilizer_nanoliter++;
        }

        $result['fertilizer'] = [
            "ml" => $fertilizer_nanoliter / 100,
            "name" => $this->fertilizer,
        ];

        $count = 0;
        while($elements['calcium']/$elements['magnesium'] > $this->ratios['calcium']) {
            foreach ($additive['real'] as $component => $value) {
                if(!isset($elements[$component])) {
                    $elements[$component] = 0;
                }
                $elements[$component] += $value / 100;
            }
            $count++;
        }
        $result['additive'] = [
            "ml" => $count/100,
            "name" => $this->additive,
            "concentration" => $additive['concentration'],
        ];
        $result["target"] = $target;

        $result['missing'] = [
            "calcium" =>  $target['calcium'] - $this->water["elements"]['calcium'],
            "magnesium" =>  $target['magnesium'] - $this->water["elements"]['magnesium'],
        ];
        $result["ratio"] = $elements['calcium'] / $elements['magnesium'];
        $result["elements"] = $elements;
        $result["dilution"] = $dilution;
        $result["water"] = 1.0 - $dilution;

        return $result;
    }

    public function getDeficiencyRatio(): array {
        if($this->water["elements"]['calcium'] > $this->water["elements"]['magnesium']) {
            return [
                "calcium"   => $this->water["elements"]['calcium'] / ($this->water["elements"]['magnesium'] ?? 1),
                "magnesium" => 1.0,
            ];
        }else if ($this->water["elements"]['calcium'] < $this->water["elements"]['magnesium']) {
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
        if($fertilizer === "") {
            $fertilizer = array_key_first($this->fertilizers);
        }
        if(!isset($this->fertilizers[$fertilizer])) {
            throw new \InvalidArgumentException("Fertilizer not found");
        }
        $this->fertilizer = $fertilizer;
    }

    public function setAdditive(string $additive): void {
        if($additive === "") {
            $additive = array_key_first($this->additives);
        }
        if(!isset($this->additives[$additive])) {
            throw new \InvalidArgumentException("Additive not found");
        }
        $this->additive = $additive;
    }

    public function setWater(array $water): void {
        $fertilizer = $this->fertilizers[$this->fertilizer];
        $additive = $this->additives[$this->additive];

        if(isset($water["elements"]["sulphate"])) {
            $water["elements"]["sulfur"] = $water["elements"]["sulphate"] * 0.334;
        }
        if (isset($water["elements"]["nitrate"])) {
            $water["elements"]["nitrogen"] = ($water["elements"]["nitrogen"] ?? 0) + ($water["elements"]["nitrate"] * 0.226);
        }
        if (isset($water["elements"]["nitrite"])) {
            $water["elements"]["nitrogen"] = ($water["elements"]["nitrogen"] ?? 0) + ($water["elements"]["nitrite"] * 0.304);
        }

        $this->water = [
            ...$water,
            "elements" => [],
        ];

        foreach($fertilizer["elements"] as $component => $value) {
            if(!isset($water["elements"][$component])) {
                $this->water["elements"][$component] = 0;
            }
            $this->water["elements"][$component] = $water["elements"][$component];
        }

        foreach($additive["real"] as $component => $value) {
            if(!isset($water["elements"][$component])) {
                $this->water["elements"][$component] = 0;
            }
            $this->water["elements"][$component] = $water["elements"][$component];
        }
    }

    public function setRatio(float $calcium, float $magnesium): void {
        $this->ratios = [
            "calcium"   => $calcium,
            "magnesium" => $magnesium,
        ];
        foreach($this->targets as $index => $target) {
            $this->targets[$index] = $this->validateTarget([
                "calcium" => $target['calcium'],
            ]);
        }
    }

    public function getWater(): array {
        return $this->water;
    }

    public function getFertilizerComponents(float $ml): array {
        $fertilizer = $this->fertilizers[$this->fertilizer];
        $result = [];

        $elements = $this->summarizeElements($fertilizer["elements"]);

        foreach($elements as $component => $value) {
            $result[$component] = ($value * 10) * $ml;
        }

        return $result;
    }

    public function getAdditiveComponents(float $ml): array {
        $additive = $this->additives[$this->additive];
        $result = [];

        foreach($additive['real'] as $component => $value) {
            $result[$component] = $value * $ml;
        }

        return $result;
    }

    public function getAdditive(): string {
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
        return $this->additives;
    }

    public function getElements(): array {
        $elements = [];
        foreach ($this->water["elements"] as $element => $value) {
            $elements[] = $element;
        }
        foreach($this->fertilizers as $fertilizer) {
            foreach($fertilizer["elements"] as $element => $value) {
                $elements[] = $element;
            }
        }
        foreach($this->additives as $additive) {
            foreach($additive["elements"] as $element => $value) {
                $elements[] = $element;
            }
        }
        return array_unique($elements);
    }
}
