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
    "calcium"   => [
        "Canna Mono Calcium" => [
            // Canna Mono Calcium 12% CaO
            "elements"      => [
                "calcium" => [
                    "CaO" => 12.0, // %
                ],
            ],
            "concentration" => 100.0, // %
            "density"       => 1.283, // g/cm³
            "unit"          => "ml",
        ],
        // Ca(C₂H₃O₂)₂ · H₂O = Calciumacetat (Calciumacetat-hydrat)
        "CaC2H3O2H2O"        => [
            // Calciumacetat
            "elements"      => [
                "calcium" => 22.7, // %
            ],
            "concentration" => 1.5, // %
            "density"       => 1.0, // g/cm³
        ],
        // Ca₃(C₆H₅O₇)₂ · 4H₂O = Calciumcitrat (Calciumcitrat-tetrahydrat)
        "Ca3C6H5O74H2O"      => [
            // Calciumcitrat
            "elements"      => [
                "calcium" => 21.1, // %
            ],
            "concentration" => 1.5, // %
            "density"       => 1.0, // g/cm³
        ],
        "CaCO3"              => [
            // Calciumcarbonat
            "elements"      => [
                "calcium" => 40.04, // %
            ],
            "concentration" => 10.0, // %
        ],
        "CaS04" => [
            // Calciumsulfat - Anhydrous
            "elements"      => [
                "calcium" => 29.45,
                "sulfur" => 23.55
            ],
            "concentration" => 10.0, // %
        ],
        "CaS042H2O" => [
            // CaSO₄·2H₂O - Calciumsulfat-Dihydrat
            "elements"      => [
                "calcium" => 23.28,
                "sulfur" => 18.63
            ],
            "concentration" => 10.0, // %
        ],
        "CaNO3" => [
            // Ca(NO₃)₂ - Calciumnitrat
            "elements"      => [
                "calcium" => 24.43,
                "nitrogen"  => 17.08, // % -> NO₃ 75,61%
            ],
            "concentration" => 10.0, // %
        ],
        "CaNO34H2O" => [
            // Ca(NO₃)₂·4H₂O - Calciumnitrat-Tetrahydrat
            "elements"      => [
                "calcium" => 16.97,
                "nitrogen"  => 11.87, // % -> NO₃ 52,52%
            ],
            "concentration" => 10.0, // %
        ],
        "Action_Gartenkalk"  => [
            "elements"      => [
                "calcium"   => [
                    "CaO" => 50.0, // %
                ],
                "magnesium" => 1.441, // % (5% MgCO3)
            ],
            "concentration" => 10.0, // %
        ],
        "SR_Organics_CalMag_Boost"  => [
            "elements"      => [
                "calcium" => 24.024, // % (60% CaCO3)
                "magnesium" => 10.09, // % (35% MgCO3)
            ],
            "concentration" => 10.0, // %
        ],
        "CaO"                => [
            // Calciumoxid
            "elements"      => [
                "calcium" => [
                    "CaO" => 100.0, // %
                ],
            ],
            "concentration" => 10.0, // %
        ],
    ],
    "magnesium" => [
        "Canna Mono Magnesium" => [
            // Canna Mono Magnesium 7% MgO
            "elements"      => [
                "magnesium" => [
                    "MgO" => 7.0, // %
                ],
            ],
            "concentration" => 100.0, // %
            "density"       => 1.23, // g/cm³
            "unit"          => "ml",
        ],
        "MgSO4-7H20"                => [
            // Magnesiumsulfat-Heptahydrat aka Epsom-Salz
            "elements"      => [
                "magnesium" => 9.86,
                "sulfur"    => 13.01, // %
            ],
            "concentration" => 10.0, // %
        ],
        "MgSO4"                => [
            // Magnesiumsulfat aka Bittersalz
            "elements"      => [
                "magnesium" => 20.195,
                "sulfur"    => 26.637, // %
            ],
            "concentration" => 10.0, // %
        ],
        "C6H6MgO7"             => [
            // Magnesiumhydrogencitrat
            "elements"      => [
                "magnesium" => [
                    "MgO" => 11.0, // %
                ],
            ],
            "concentration" => 10.0, // %
        ],
        "C12H10Mg3O14"         => [
            // Trimagnesiumdicitrat
            "elements"      => [
                "magnesium" => 16.18, // %
            ],
            "concentration" => 10.0, // %
        ],
        "MgCO3"                => [
            // Magnesiumcarbonat
            "elements"      => [
                "magnesium" => 28.83, // %
            ],
            "concentration" => 10.0, // %
        ],
        "MgO"                => [
            // Magnesiumoxid
            "elements"      => [
                "magnesium" => [
                    "MgO" => 100.0, // %
                ],
            ],
            "concentration" => 10.0, // %
        ],
    ],
];