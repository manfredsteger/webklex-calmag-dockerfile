<?php
/*
* File: fertilizers.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 19:53
* Updated: -
*
* Description:
*  -
*/

return [
    "BioBizz - CalMag"                              => [
        "elements" => [
            "calcium"   => [
                "CaO" => 4.2, // % CaO 4.2% -> 4.2 * 0.7143 = 3
            ],
            "magnesium" => [
                "MgO" => 1.7, // % MgO 1.7% -> 1.7 * 0.6032 = 1.02544
            ],
        ],
        "density"       => 1.1, // g/cm³
    ],
    "Canna - CalMag Agent"                          => [
        "elements" => [
            "calcium"   => 5.6, // %
            "magnesium" => 1.7, // %
        ],
        "density"       => 1.0, // g/cm³
    ],
    "Terra Aquatica - Calcium-Magnesium Supplement" => [
        "elements" => [
            "calcium"   => 4, // %
            "magnesium" => 1, // %
        ],
        "density"       => 1.0, // g/cm³
    ],
    "ATA - CalMag"                                  => [
        "elements" => [
            "calcium"   => [
                "CaO" => 6.8, // % CaO 6.8% -> 6.8 * 0.7143 = 4.828
            ],
            "magnesium" => [
                "MgO" => 2.5, // % MgO 2.5% -> 2.5 * 0.6032 = 1.5075
            ],
            "nitrogen"  => 5.8, // %
            "iron"      => 0.03, // %
        ],
        "density"       => 1.0, // g/cm³
    ],
    "Plagron - CalMag Pro"                          => [
        "elements" => [
            "calcium"   => [
                "CaO" => 5.7, // % CaO 5.7% -> 5.7 * 0.7143 = 4.047
            ],
            "magnesium" => [
                "MgO" => 3.3, // % MgO 3.3% -> 3.3 * 0.6032 = 1.9899
            ],
            "nitrogen"  => 5.1, // %
        ],
        "density"       => 1.0, // g/cm³
    ],
    "Mills - CalMag"                                => [
        "elements" => [
            "calcium"   => 13.1, // %
            "magnesium" => 2.5, // %
            "nitrogen"  => 8.3, // %
        ],
        "density"       => 1.0, // g/cm³
    ],
    "Xpert Cal-Mag Amino"                           => [
        "elements" => [
            "nitrogen" => [ // Gesamtstickstoff (N) - 4%
                "NO3" => 3.8, // Nitratstickstoff (NO3) - 3.8%
                "NH4" => 0.2, // Ammoniumstickstoff (NH4) -0.2%
            ],
            "calcium"   => 3.4, // Kalzium (Ca) - 3.4%,
            "magnesium" => 1.1, // Magnesium (Mg) - 1.1%,
            "iron"      => 0.1, // Chelatiertes Eisen (Fe) - 0.1%,
            "manganese" => 0.05, // Chelatiertes Mangan (Mn) - 0.05%,
            "zinc"      => 0.05, // Chelatiertes Zink (Zn) - 0.05%,
        ],
        "density"       => 1.0, // g/cm³
    ],
];