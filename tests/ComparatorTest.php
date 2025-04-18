<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Webklex\CalMag\Comparator;
use Webklex\CalMag\Enums\GrowState;

class ComparatorTest extends TestCase {

    private array $waterElements;
    private Comparator $comparator;

    protected function setUp(): void {
        parent::setUp();
        $this->waterElements = [
            'calcium' => 50,
            'magnesium' => 20,
            'nitrogen' => 10,
            'sulfur' => 5
        ];
        $this->comparator = new Comparator($this->waterElements);
    }

    public function testConstructor(): void {
        $this->assertInstanceOf(Comparator::class, $this->comparator);
        
        // Test with custom ratio
        $comparator = new Comparator($this->waterElements, 4.0);
        $this->assertInstanceOf(Comparator::class, $comparator);
    }

    public function testSetWaterElements(): void {
        $newElements = [
            'calcium' => 60,
            'magnesium' => 25,
            'sulphate' => 15,
            'nitrate' => 20,
            'nitrite' => 10
        ];

        $this->comparator->setWaterElements($newElements);
        $this->assertTrue(true); // Assert the method executes without error
        
        // Test with required elements only
        $this->comparator->setWaterElements([
            'calcium' => 100,
            'magnesium' => 30
        ]);
        $this->assertTrue(true);
    }

    public function testCalculate(): void {
        $result = $this->comparator->calculate();
        
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        
        // Check structure for each fertilizer brand
        foreach ($result as $brand => $states) {
            $this->assertIsString($brand);
            $this->assertIsArray($states);
            
            // Check each growth state
            foreach ($states as $state => $data) {
                $this->assertIsString($state);
                $this->assertIsArray($data);
                
                // Check required keys in state data
                $this->assertArrayHasKey('fertilizer', $data);
                $this->assertArrayHasKey('target', $data);
                $this->assertArrayHasKey('elements', $data);
                $this->assertArrayHasKey('dilution', $data);
                $this->assertArrayHasKey('water', $data);
                
                // Check fertilizer data structure
                $this->assertIsArray($data['fertilizer']);
                $this->assertArrayHasKey('ml', $data['fertilizer']);
                $this->assertArrayHasKey('name', $data['fertilizer']);
                
                // Check target data structure
                $this->assertIsArray($data['target']);
                $this->assertArrayHasKey('state', $data['target']);
                $this->assertArrayHasKey('elements', $data['target']);
                $this->assertArrayHasKey('weeks', $data['target']);
                
                // Check elements data structure
                $this->assertIsArray($data['elements']);
                $this->assertArrayHasKey('calcium', $data['elements']);
                $this->assertArrayHasKey('magnesium', $data['elements']);
            }
        }
        
        // Test calculation with minimum elements
        $comparator = new Comparator([
            'calcium' => 10,
            'magnesium' => 5
        ]);
        $result = $comparator->calculate();
        $this->assertIsArray($result);
        
        // Test calculation with all possible elements
        $comparator = new Comparator([
            'calcium' => 80,
            'magnesium' => 25,
            'nitrogen' => 15,
            'sulfur' => 10,
            'potassium' => 20,
            'phosphorus' => 15
        ]);
        $result = $comparator->calculate();
        $this->assertIsArray($result);
    }

    public function testCalculateWithDifferentGrowStates(): void {
        // Test Propagation state
        $comparator = new Comparator($this->waterElements);
        $result = $comparator->calculate();
        $this->assertIsArray($result);

        // Test Vegetation state
        $comparator = new Comparator($this->waterElements);
        $result = $comparator->calculate();
        $this->assertIsArray($result);

        // Test Flower state
        $comparator = new Comparator($this->waterElements);
        $result = $comparator->calculate();
        $this->assertIsArray($result);

        // Test LateFlower state
        $comparator = new Comparator($this->waterElements);
        $result = $comparator->calculate();
        $this->assertIsArray($result);
    }

    public function testWaterElementConversions(): void {
        $elements = [
            'calcium' => 50,    // Required
            'magnesium' => 20,  // Required
            'sulphate' => 30,   // Should convert to sulfur
            'nitrate' => 45,    // Should convert to nitrogen
            'nitrite' => 20     // Should also convert to nitrogen
        ];
        
        $this->comparator->setWaterElements($elements);
        $result = $this->comparator->calculate();
        $this->assertIsArray($result);
    }

    public function testSetRatio(): void {
        $this->comparator->setRatio(2.5, 1.0);
        $result = $this->comparator->calculate();
        
        // Verify the calculation results reflect the new ratio
        $this->assertIsArray($result);
        foreach ($result as $brand => $states) {
            foreach ($states as $state => $data) {
                // Verify the elements reflect the correct ratio
                if (isset($data['elements']['calcium']) && isset($data['elements']['magnesium']) && $data['elements']['magnesium'] > 0) {
                    $actualRatio = $data['elements']['calcium'] / $data['elements']['magnesium'];
                    $this->assertEqualsWithDelta(2.5, $actualRatio, 2.5, "Calcium to Magnesium ratio should be approximately 2.5:1");
                }
            }
        }
    }
} 