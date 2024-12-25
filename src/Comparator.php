<?php
/*
* File: Comparitor.php
* Category: -
* Author: M.Goldenbaum
* Created: 16.11.24 00:17
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\CalMag;

use Webklex\CalMag\Enums\GrowState;

/**
 * Class Comparator
 *
 * @package Webklex\CalMag
 */
class Comparator {

    /**
     * @var array $water_elements The water
     */
    protected array $water_elements;

    /**
     * @var array $targets The targets
     */
    protected array $targets;

    /**
     * @var array $ratios The ratios
     */
    protected array $ratios;

    public function __construct(array $water_elements, float $ratio = 3.5) {
        $this->ratios = [
            "calcium"   => $ratio,
            "magnesium" => 1.0,
        ];

        foreach (Config::get("app.targets", []) as $index => $target) {
            $this->targets[$index] = $this->validateTarget(GrowState::fromString($index), [
                ...$target,
                "elements" => [
                    ...$target["elements"] ?? [],
                    "calcium" => $target['calcium'] ?? 0,
                    "magnesium" => $target['magnesium'] ?? 0,
                ]
            ]);
        }

        $this->setWaterElements($water_elements);
    }

    public function calculate(): array {
        $result = [];

        $calculator = new Calculator(["elements" => $this->water_elements,], "", ["calcium" => "", "magnesium" => ""], $this->ratios["calcium"]);
        $fertilizers = $calculator->getFertilizers();
        foreach($fertilizers as $fertilizer_name => $fertilizer) {
            $calculator = new Calculator(["elements" => $this->water_elements,], $fertilizer_name, ["calcium" => "", "magnesium" => ""], $this->ratios["calcium"]);
            $result[$fertilizer_name] = $calculator->getAppliedFertilizer();
        }

        return $result;
    }

    public function setWaterElements(array $water_elements): void {
        if (isset($water_elements["sulphate"])) {
            $water_elements["sulfur"] = $water_elements["sulphate"] * 0.334;
        }
        if (isset($water_elements["nitrate"])) {
            $water_elements["nitrogen"] = ($water_elements["nitrogen"] ?? 0) + ($water_elements["nitrate"] * 0.226);
        }
        if (isset($water_elements["nitrite"])) {
            $water_elements["nitrogen"] = ($water_elements["nitrogen"] ?? 0) + ($water_elements["nitrite"] * 0.304);
        }
        /*if(!isset($water_elements["magnesium"])) {
            $water_elements["magnesium"] = 0.0001;
        }
        if(!isset($water_elements["calcium"])) {
            $water_elements["calcium"] = 0.0001;
        }*/

        $this->water_elements = $water_elements;
    }

    public function setRatio(float $calcium, float $magnesium): void {
        $this->ratios = [
            "calcium"   => $calcium,
            "magnesium" => $magnesium,
        ];
        foreach ($this->targets as $index => $target) {
            $this->targets[$index] = $this->validateTarget(GrowState::fromString($index), [
                ...$target,
                "elements" => [
                    ...$target["elements"] ?? [],
                    "calcium" => $target['calcium'] ?? 0,
                    "magnesium" => $target['magnesium'] ?? 0,
            ]]);
        }
    }

    protected function validateTarget(GrowState $state, array $target): array {
        $elements = $target['elements'] ?? [];
        if (!isset($elements['calcium'])) {
            $elements['calcium'] = $elements['magnesium'] * $this->ratios['calcium'];
        } elseif (!isset($target['magnesium']) || $elements['magnesium'] <= 0) {
            $elements['magnesium'] = $elements['calcium'] / $this->ratios['calcium'];
        }
        return array_merge($elements, (match ($state) {
            GrowState::Propagation => [
                "days" => $target['days'] ?? 7,
                "ph"   => $target['ph'] ?? 6.3,
            ],
            GrowState::Vegetation => [
                "days" => $target['days'] ?? 3 * 7,
                "ph"   => $target['ph'] ?? 6.3,
            ],
            GrowState::Flower, GrowState::LateFlower => [
                "days" => $target['days'] ?? 4 * 7,
                "ph"   => $target['ph'] ?? 6.3,
            ],
        }));
    }
}