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
    ],
];