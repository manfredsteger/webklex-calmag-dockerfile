<?php
/*
* File: additives.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 19:51
* Updated: -
*
* Description:
*  -
*/

return [
    "MgSO4"        => [
        // Magnesiumsulfat
        "elements"      => [
            "magnesium" => [
                "MgO" => 16.0, // %
            ],
            "sulfur"    => 13.0, // %
        ],
        "concentration" => 2.0, // %
    ],
    "C6H6MgO7"     => [
        // Magnesiumhydrogencitrat
        "elements"      => [
            "magnesium" => [
                "MgO" => 11.0, // %
            ],
        ],
        "concentration" => 16.0, // %
    ],
    "C12H10Mg3O14" => [
        // Trimagnesiumdicitrat
        "elements"      => [
            "magnesium" => 16.0, // %
        ],
        "concentration" => 16.0, // %
    ],
    "Canna Mono"   => [
        // Canna Mono Magnesium 7% MgO
        "elements"      => [
            "magnesium" => [
                "MgO" => 100.0, // %
            ],
        ],
        "concentration" => 7.0, // %
    ],
];