<?php

use Webklex\CalMag\Enums\GrowState;

return [
    "version" => "2.3.0",
    "elements" => [
        "calcium"   => 0.0, // mg/L
        "magnesium" => 0.0, // mg/L
        "potassium" => 0.0, // mg/L
        "iron"      => 0.0, // mg/L
        "sulphate"  => 0.0, // mg/L
        "nitrate"   => 0.0, // mg/L
        "nitrite"   => 0.0, // mg/L
    ],
    "regions" => [
        "us" => "region.option.us",
        "de" => "region.option.de",
    ],
    'available_elements' => [
        "calcium"   => "content.form.element.calcium.label",
        "magnesium" => "content.form.element.magnesium.label",
    ],
    'expert_elements' => [
        "calcium"   => "content.form.element.calcium.label",
        "magnesium" => "content.form.element.magnesium.label",
        "nitrogen"  => "content.form.element.nitrogen.label",
        "nitrate"   => "content.form.element.nitrate.label",
        "nitrite"   => "content.form.element.nitrite.label",
        "sulfur"    => "content.form.element.sulfur.label",
        "sulphate"  => "content.form.element.sulphate.label",
        "chloride"  => "content.form.element.chloride.label",
    ],
    "models" => [
        "fumus" => [
            1 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["calcium"   => 60], // mg/L
            ],
            2 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 80], // mg/L
            ],
            3 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 80], // mg/L
            ],
            4 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 80], // mg/L
            ],
            5 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 100], // mg/L
            ],
            6 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 100], // mg/L
            ],
            7 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 100], // mg/L
            ],
            8 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 100], // mg/L
            ],
            9 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 130], // mg/L
            ],
            10 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 130], // mg/L
            ],
            11 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 130], // mg/L
            ],
            12 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 130], // mg/L
            ],
        ],
        "linear" => [
            1 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["calcium"   => 60], // mg/L
            ],
            2 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 66.85], // mg/L
            ],
            3 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 73.37], // mg/L
            ],
            4 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 80], // mg/L
            ],
            5 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 85.11], // mg/L
            ],
            6 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 90.01], // mg/L
            ],
            7 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 94.90], // mg/L
            ],
            8 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 100], // mg/L
            ],
            9 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 107.62], // mg/L
            ],
            10 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 115.12], // mg/L
            ],
            11 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 122.62], // mg/L
            ],
            12 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 130], // mg/L
            ],
        ],
        "dynamic_ca" => [
            1 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["calcium"   => 50], // mg/L
            ],
            2 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["calcium"   => 80], // mg/L
            ],
            3 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 100], // mg/L
            ],
            4 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 120], // mg/L
            ],
            5 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 130], // mg/L
            ],
            6 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 130], // mg/L
            ],
            7 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 125], // mg/L
            ],
            8 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 120], // mg/L
            ],
            9 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 115], // mg/L
            ],
            10 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 110], // mg/L
            ],
            11 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 100], // mg/L
            ],
            12 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 90], // mg/L
            ],
            13 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 80], // mg/L
            ],
            14 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 70], // mg/L
            ],
            15 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 60], // mg/L
            ],
            16 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 50], // mg/L
            ],
        ],
        "dynamic_mg" => [
            1 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["magnesium"   => 10], // mg/L
            ],
            2 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["magnesium"   => 20], // mg/L
            ],
            3 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["magnesium"   => 30], // mg/L
            ],
            4 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["magnesium"   => 40], // mg/L
            ],
            5 => [
                "state" => GrowState::Flower->value,
                "elements" => ["magnesium"   => 45], // mg/L
            ],
            6 => [
                "state" => GrowState::Flower->value,
                "elements" => ["magnesium"   => 45], // mg/L
            ],
            7 => [
                "state" => GrowState::Flower->value,
                "elements" => ["magnesium"   => 40], // mg/L
            ],
            8 => [
                "state" => GrowState::Flower->value,
                "elements" => ["magnesium"   => 35], // mg/L
            ],
            9 => [
                "state" => GrowState::Flower->value,
                "elements" => ["magnesium"   => 35], // mg/L
            ],
            10 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["magnesium"   => 40], // mg/L
            ],
            11 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["magnesium"   => 50], // mg/L
            ],
            12 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["magnesium"   => 60], // mg/L
            ],
            13 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["magnesium"   => 60], // mg/L
            ],
            14 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["magnesium"   => 55], // mg/L
            ],
            15 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["magnesium"   => 50], // mg/L
            ],
            16 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["magnesium"   => 40], // mg/L
            ],
        ],
        "dynamic_ca_mg" => [
            1 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["calcium"   => 50, "magnesium" => 10], // mg/L
            ],
            2 => [
                "state" => GrowState::Propagation->value,
                "elements" => ["calcium"   => 80, "magnesium" => 20], // mg/L
            ],
            3 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 100, "magnesium" => 30], // mg/L
            ],
            4 => [
                "state" => GrowState::Vegetation->value,
                "elements" => ["calcium"   => 120, "magnesium" => 40], // mg/L
            ],
            5 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 130, "magnesium" => 45], // mg/L
            ],
            6 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 130, "magnesium" => 45], // mg/L
            ],
            7 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 125, "magnesium" => 40], // mg/L
            ],
            8 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 120, "magnesium" => 35], // mg/L
            ],
            9 => [
                "state" => GrowState::Flower->value,
                "elements" => ["calcium"   => 115, "magnesium" => 35], // mg/L
            ],
            10 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 110, "magnesium" => 40], // mg/L
            ],
            11 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 100, "magnesium" => 50], // mg/L
            ],
            12 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 90, "magnesium" => 60], // mg/L
            ],
            13 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 80, "magnesium" => 60], // mg/L
            ],
            14 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 70, "magnesium" => 55], // mg/L
            ],
            15 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 60, "magnesium" => 50], // mg/L
            ],
            16 => [
                "state" => GrowState::LateFlower->value,
                "elements" => ["calcium"   => 50, "magnesium" => 40], // mg/L
            ],
        ],
    ],
];