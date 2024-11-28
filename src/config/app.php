<?php

use Webklex\CalMag\Enums\GrowState;

return [
    "version" => "1.5.0",
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
    "targets" => [
        GrowState::Propagation->value => [
            "elements" => [
                "calcium" => 60, // mg/L
            ],
            "weeks" => 1,
        ],
        GrowState::Vegetation->value => [
            "elements" => [
                //"magnesium" => 26.67, // mg/L
                "calcium" => 80, // mg/L
            ],
            "weeks" => 3,
        ],
        GrowState::Flower->value     => [
            "elements" => [
                //"magnesium" => 33.33, // mg/L
                "calcium" => 100, // mg/L
            ],
            "weeks" => 4,
        ],
        GrowState::LateFlower->value => [
            "elements" => [
                //"magnesium" => 43.33, // mg/L
                "calcium" => 130, // mg/L
            ],
            "weeks" => 4,
        ],
    ],
];