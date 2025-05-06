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
    "canna"   => [
        "brand_name" => "Canna",
        "link"       => [
            "de" => "https://www.canna-de.com/",
            "us" => "https://www.canna-uk.com/",
        ],
        "products"   => [
            "calmag_agent" => [
                "name"     => "CalMag Agent",
                "elements" => [
                    "calcium"   => 5.6, // %
                    "magnesium" => 1.7, // %
                    "nitrogen"  => 3.28, // %
                ],
                "density"  => 1.258, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://www.canna-de.com/calmag-agent",
                    "us" => "https://www.canna-uk.com/calmag-agent",
                ],
            ],
        ],
    ],

    "biobizz" => [
        "brand_name" => "BioBizz",
        "link"       => [
            "de" => "https://www.biobizz.com/de/",
            "us" => "https://www.biobizz.com/",
        ],
        "products"   => [
            "calmag" => [
                "name"     => "CalMag",
                "elements" => [
                    "calcium"   => [
                        "CaO" => 4.2, // % CaO 4.2% -> 4.2 * 0.7143 = 3
                    ],
                    "magnesium" => [
                        "MgO" => 1.7, // % MgO 1.7% -> 1.7 * 0.6032 = 1.02544
                    ],
                ],
                "density"  => 1.087, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://www.biobizz.com/de/producto/calmag/",
                    "us" => "https://www.biobizz.com/producto/calmag/",
                ],
            ],
        ],
    ],

    "terra_aquatica" => [
        "brand_name" => "Terra Aquatica",
        "link"       => [
            "de" => "https://www.terraaquatica.com/de/",
            "us" => "https://www.terraaquatica.com/",
        ],
        "products"   => [
            "calcium_magnesium_supplement" => [
                "name"     => "Calcium-Magnesium Supplement",
                "elements" => [
                    "calcium"   => 4, // %
                    "magnesium" => 1, // %
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://www.terraaquatica.com/de/spezialdunger-und-nahrstoffzusatze/calcium-magnesium-supplement-4/",
                    "us" => "https://www.terraaquatica.com/specific-fertilisers-nutritional-supplements/calcium-magnesium-supplement/",
                ],
            ],
        ],
    ],

    "atami" => [
        "brand_name" => "Atami",
        "link"       => [
            "de" => "https://atami.com/de/",
            "us" => "https://atami.com/",
        ],
        "products"   => [
            "calmag" => [
                "name"     => "ATA CalMag",
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
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://atami.com/de/produkt/ata-calmag/",
                    "us" => "https://atami.com/product/ata-calmag/",
                ],
            ],
        ],
    ],

    "plagron" => [
        "brand_name" => "Plagron",
        "link"       => [
            "de" => "https://plagron.com/de/hobby",
            "us" => "https://plagron.com/en/hobby",
        ],
        "products"   => [
            "calmag_pro" => [
                "name"     => "CalMag Pro",
                "elements" => [
                    "calcium"   => [
                        "CaO" => 5.7, // % CaO 5.7% -> 5.7 * 0.7143 = 4.047
                    ],
                    "magnesium" => [
                        "MgO" => 3.3, // % MgO 3.3% -> 3.3 * 0.6032 = 1.9899
                    ],
                    "nitrogen"  => 5.1, // %
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://plagron.com/de/hobby/produkte/calmag-pro",
                    "us" => "https://plagron.com/en/hobby/products/calmag-pro",
                ],
            ],
        ],
    ],

    "mills_nutrients" => [
        "brand_name" => "Mills Nutrients",
        "link"      => [
            "de" => "https://mills-nutrients.com/",
            "us" => "https://mills-nutrients.com/",
        ],
        "products"   => [
            "calmag" => [
                "name"     => "CalMag",
                "elements" => [
                    "calcium"   => 13.1, // %
                    "magnesium" => 2.5, // %
                    "nitrogen"  => 8.3, // %
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"      => [
                    "de" => "https://mills-nutrients.com/product/mills-calmag",
                    "us" => "https://mills-nutrients.com/product/mills-calmag",
                ],
            ],
        ],
    ],

    "420flow" => [
        "brand_name" => "420Flow",
        "link"       => [
            "de" => "https://420flow.de/",
            "us" => "https://420flow.de/",
        ],
        "products"   => [
            "calmag" => [
                "name"     => "Das Bio CalMag",
                "elements" => [
                    "calcium"   => [
                        "CaO" => 2.0, // %
                    ],
                    "magnesium" => [
                        "MgO" => 1.41, // %
                    ],
                    "humic_acids" => 0.018, // %
                    // "potassium" => ["K2O" => 0.0063, // %], // %
                    // "nitrogen"  => 0.00008, // %
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://420flow.de/products/das-bio-calmag-calcium-und-magnesium",
                    "us" => "https://420flow.de/products/das-bio-calmag-calcium-und-magnesium",
                ],
            ],
        ],
    ],

    "xpert" => [
        "brand_name" => "Xpert",
        "link"       => [
            "de" => "https://www.xpert-fertilizer.com/",
            "us" => "https://www.xpert-fertilizer.com/",
        ],
        "products"   => [
            "calmag_amino" => [
                "name"     => "Cal-Mag Amino",
                "elements" => [
                    "nitrogen"  => [ // Gesamtstickstoff (N) - 4%
                        "NO3" => 3.8, // Nitratstickstoff (NO3) - 3.8%
                        "NH4" => 0.2, // Ammoniumstickstoff (NH4) -0.2%
                    ],
                    "calcium"   => 3.4, // Kalzium (Ca) - 3.4%,
                    "magnesium" => 1.1, // Magnesium (Mg) - 1.1%,
                    "iron"      => 0.1, // Chelatiertes Eisen (Fe) - 0.1%,
                    "manganese" => 0.05, // Chelatiertes Mangan (Mn) - 0.05%,
                    "zinc"      => 0.05, // Chelatiertes Zink (Zn) - 0.05%,
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://xpertnutrients.com/de/portfolio/cal-mag-amino/",
                    "us" => "https://xpertnutrients.com/en/portfolio/cal-mag-amino/",
                ],
            ],
        ],
    ],

    "aptus" => [
        "brand_name" => "Aptus Plant Tech",
        "link"       => [
            "de" => "https://aptus-holland.com/de/",
            "us" => "https://aptus-holland.com/",
        ],
        "products"   => [
            "camg_boost" => [
                "name"     => "CaMg-BOOST",
                "elements" => [
                    "nitrogen"  => [ // Gesamtstickstoff (N) - 4%
                        "NO3" => 7.0, // Nitratstickstoff (NO3)
                        "organic" => 3.0, // Organischer Stickstoff
                    ],
                    "calcium"   => [
                        "CaO" => 9.0, // %
                    ],
                    "magnesium" => [
                        "MgO" => 2.0, // %
                    ],
                ],
                "density"  => 1.1, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://aptus-holland.com/de/produkte/camg-boost/",
                    "us" => "https://aptus-holland.com/products/camg-boost/",
                ],
            ],
        ],
    ],

    "fmax" => [
        "brand_name" => "F-Max",
        "link"       => [
            "de" => "https://f-max.eu/?lang=de",
            "us" => "https://f-max.eu/?lang=en",
        ],
        "products"   => [
            "calmag" => [
                "name"     => "CalMag",
                "elements" => [
                    "calcium"   => [
                        "CaO" => 5.0, // %
                    ],
                    "magnesium" => [
                        "MgO" => 1.5, // %
                    ],
                    "nitrogen"  => [
                        "NO3" => 5.0, // %
                    ],
                    "iron"      => [
                        "Fe" => 0.015, // %
                    ],
                    "boron"     => [
                        "B" => 0.002, // %
                    ],
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://f-max.eu/?page_id=1640&lang=de",
                    "us" => "https://f-max.eu/?page_id=1637&lang=en",
                ],
            ],
        ],
    ],

    "advanced_nutrients" => [
        "brand_name" => "Advanced Nutrients",
        "link"       => [
            "de" => "https://www.advancednutrients.com/de-de/",
            "us" => "https://www.advancednutrients.com/",
        ],
        "products"   => [
            "sensi_cal_mag_xtra" => [
                "name"     => "Sensi Cal Mag Xtra®",
                "elements" => [
                    "calcium"   => 3.2, // %
                    "magnesium" => 1.1, // %
                    "nitrogen"  => [
                        "NO3" => 4.0, // % Nitratstickstoff (NO3)
                    ],
                    "iron"      => 0.09, // % Chelatiertes Eisen (Fe)
                    "manganese" => 0.05, // % Chelatiertes Mangan (Mn)
                    "zinc"      => 0.05, // % Chelatiertes Zink (Zn)
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://www.advancednutrients.com/de/products/sensi-cal-mag-xtra/",
                    "us" => "https://www.advancednutrients.com/products/sensi-cal-mag-xtra/",
                ],
            ],
            "og_sensi_cal_mag_xtra" => [
                "name"     => "OG Organics™ Sensi Cal Mag Xtra®",
                "elements" => [
                    "calcium"   => 1.5, // %
                    "magnesium" => 1.1, // %
                    "sulfur"    => 1.8, // %
                    "iron"      => 0.1, // % Chelatiertes Eisen (Fe)
                    "zinc"      => 0.07, // % Chelatiertes Zink (Zn)
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "us" => "https://www.advancednutrients.com/products/og-organics-sensi-cal-mag-xtra/",
                    "de" => "https://www.advancednutrients.com/de/products/og-organics-sensi-cal-mag-xtra/",
                ],
            ],
        ],
    ],

    "athena" => [
        "brand_name" => "Athena",
        "link"       => [
            "de" => "https://www.athenaag.com/",
            "us" => "https://www.athenaag.com/",
        ],
        "products"   => [
            "camg" => [
                "name"     => "CaMg",
                "elements" => [
                    "calcium"   => 2.1, // %
                    "magnesium" => 1.1, // %
                    "nitrogen"  => 2.0, // %
                    "iron"      => 0.06, // %
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://store.athenaag.com/camg",
                    "us" => "https://store.athenaag.com/camg",
                ],
            ],
        ],
    ],

    "hy_pro" => [
        "brand_name" => "Hy-Pro",
        "link"       => [
            "de" => "https://hy-pro.nl/de/",
            "us" => "https://www.hy-pro.nl/",
        ],
        "products"   => [
            "calmag" => [
                "name"     => "Cal-Mag",
                "elements" => [
                    "nitrogen"  => [ // Gesamtstickstoff (N) - 4%
                        "NO3" => 9.3105, // Nitratstickstoff (NO3)
                    ],
                    "calcium"   => 2.1978, // %
                    "magnesium" => 0.4917, // %
                ],
                "density"  => 1.05, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://hy-pro.nl/de/produkt/hypro-calmag-2/",
                    "us" => "https://hy-pro.nl/product/hypro-calmag/",
                ],
            ],
        ],
    ],

    "organicsnutrients" => [
        "brand_name" => "Organics Nutrients",
        "link"       => [
            "de" => "https://www.organicsnutrients.com/de/",
            "us" => "https://www.organicsnutrients.com/en/",
        ],
        "products"   => [
            "calmag" => [
                "name"     => "CalMag Alga",
                "elements" => [
                    "calcium"   => 80.0*0.40, // %
                    "magnesium" => 12.0*0.288, // %
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://www.organicsnutrients.com/de/produkt/calmag-alga/",
                    "us" => "https://www.organicsnutrients.com/en/products/calmag-alga/",
                ],
            ],
        ],
    ],

    "homegrow24" => [
        "brand_name" => "Homegrow24",
        "link"       => [
            "de" => "https://homegrow24.store",
            "us" => "https://homegrow24.store",
        ],
        "products"   => [
            "calcium_magnesium_supplement" => [
                "name"     => "CALMAG",
                "elements" => [
                    "nitrogen"  => [ // Gesamtstickstoff (N) - 4%
                        "NO3" => 2.36, // Nitratstickstoff (NO3) 2.36%
                        "NH4" => 0.14, // Ammoniumstickstoff (NH4) 0.14%
                    ],
                    "calcium"   => [
                        "CaO" => 4.0,
                    ],
                    "magnesium" => [
                        "MgO" => 1.0,
                    ],
                ],
                "density"  => 1.0, // g/cm³
                "ph"       => 7.0, // pH-Wert
                "schedule" => false,
                "link"     => [
                    "de" => "https://homegrow24.store/products/calmag-pflanzen-supplement",
                    "us" => "https://homegrow24.store/products/calmag-pflanzen-supplement",
                ],
            ],
        ],
    ],
];