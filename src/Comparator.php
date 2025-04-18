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
 * Comparator class for analyzing and comparing different fertilizer combinations
 * 
 * This class provides functionality to compare different fertilizers and their effects
 * on nutrient ratios. It helps in determining the most suitable fertilizer combination
 * for achieving target nutrient levels.
 *
 * @package Webklex\CalMag
 */
class Comparator {

    /**
     * @var array The water element concentrations
     */
    protected array $water_elements;

    /**
     * @var array The target models for different growth stages
     */
    protected array $models;

    /**
     * @var array The target ratios for calcium and magnesium
     */
    protected array $ratios = [
        "calcium"   => 3.5,
        "magnesium" => 1.0,
    ];

    protected $target_model = "linear";

    /**
     * Constructor initializes the comparator with water composition and target ratio
     * 
     * @param array $water_elements Array of element concentrations in water
     * @param float $ratio Target calcium to magnesium ratio (default: 3.5)
     */
    public function __construct(array $water_elements, float $ratio = 3.5, $target_model = "linear") {
        $this->ratios = [
            "calcium"   => $ratio,
            "magnesium" => 1.0,
        ];
        $this->target_model = $target_model;

        foreach (Config::get("app.models.".$this->target_model, []) as $week => $target) {
            $this->models[$week] = $this->validateTarget(GrowState::fromString($target["state"]), [
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

    /**
     * Calculate comparison results for all available fertilizers
     * 
     * Creates a calculator instance for each fertilizer and compares their
     * effectiveness in achieving the target nutrient levels.
     * 
     * @return array Results for each fertilizer
     */
    public function calculate(): array {
        $result = [];

        $calculator = new Calculator(["elements" => $this->water_elements,], "", ["calcium" => "", "magnesium" => ""], $this->ratios["calcium"]);
        $fertilizers = $calculator->getFertilizers();
        foreach($fertilizers as $fertilizer_name => $fertilizer) {
            $calculator = new Calculator(["elements" => $this->water_elements,], $fertilizer_name, ["calcium" => "", "magnesium" => ""], $this->ratios["calcium"]);
            $calculator->setDilutionSupport(true);
            $result[$fertilizer_name] = $calculator->getAppliedFertilizer();
        }

        return $result;
    }

    /**
     * Set the water element concentrations and convert units if needed
     * 
     * Handles unit conversions for various compounds:
     * - Sulphate to Sulfur
     * - Nitrate and Nitrite to Nitrogen
     * 
     * @param array $water_elements Array of element concentrations
     * @return void
     */
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

        $this->water_elements = $water_elements;
    }

    /**
     * Set the target ratios for calcium and magnesium
     * 
     * Updates the target ratios and recalculates all model targets
     * based on the new ratios.
     * 
     * @param float $calcium Target calcium ratio
     * @param float $magnesium Target magnesium ratio
     * @return void
     */
    public function setRatio(float $calcium, float $magnesium): void {
        $this->ratios = [
            "calcium"   => $calcium,
            "magnesium" => $magnesium,
        ];
        foreach ($this->models as $week => $target) {
            if(($target['calcium'] ?? 0) > 0) {
                $target['magnesium'] = $target['calcium'] / $this->ratios['calcium'];
            } elseif(($target['magnesium'] ?? 0) > 0) {
                $target['calcium'] = $target['magnesium'] * $this->ratios['calcium'];
            } else {
                $target['calcium'] = 0;
                $target['magnesium'] = 0;
            }
            $this->models[$week] = $this->validateTarget(GrowState::fromString($target["state"]), [
                ...$target,
                "elements" => [
                    ...$target["elements"] ?? [],
                    "calcium" => $target['calcium'],
                    "magnesium" => $target['magnesium'],
            ]]);
        }
    }

    /**
     * Validate and normalize target values for a growth state
     * 
     * Ensures that calcium and magnesium values maintain the proper ratio
     * and adds default values based on the growth state.
     * 
     * @param GrowState $state The growth state (Propagation, Vegetation, Flower, LateFlower)
     * @param array $target The target configuration
     * @return array Validated and normalized target configuration
     */
    protected function validateTarget(GrowState $state, array $target): array {
        $elements = $target['elements'] ?? [];
        if (!isset($elements['calcium'])) {
            $elements['calcium'] = $elements['magnesium'] * $this->ratios['calcium'];
        } elseif (!isset($target['magnesium']) || $elements['magnesium'] <= 0) {
            $elements['magnesium'] = $elements['calcium'] / $this->ratios['calcium'];
        }
        return array_merge($elements, (match ($state) {
            GrowState::Propagation => [
                "state" => $state->value,
                "days" => $target['days'] ?? 7,
                "ph"   => $target['ph'] ?? 6.3,
            ],
            GrowState::Vegetation => [
                "state" => $state->value,
                "days" => $target['days'] ?? 3 * 7,
                "ph"   => $target['ph'] ?? 6.3,
            ],
            GrowState::Flower, GrowState::LateFlower => [
                "state" => $state->value,
                "days" => $target['days'] ?? 4 * 7,
                "ph"   => $target['ph'] ?? 6.3,
            ],
        }));
    }
}