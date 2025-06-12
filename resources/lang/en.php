<?php
/*
* File: en.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 23:07
* Updated: -
*
* Description:
*  -
*/

return [
    "region"   => [
        "default" => "us",
        "option"  => [
            "us" => "United States of America",
            "de" => "Germany",
        ]
    ],
    "header"   => [
        "logo" => [
            "appendix" => "Calculator",
        ],
        "og"   => [
            "title"       => "CalMag Rechner - Optimiere dein Gießwasser",
            "description" => "Berechne einfach das perfekte Calcium-zu-Magnesium-Verhältnis für gesündere Pflanzen und besseres Wachstum.",
        ],
    ],
    "footer"   => [
        "impress" => "Impress & Contact",
    ],
    "dilution" => [
        "stock" => "Your water",
        "water" => "Distilled water",
        "label" => "Dilution ratio",
    ],
    "additive" => [
        "MgSO4"                => "Magnesiumsulfate - Magnesiumsulfate",
        "MgSO4-7H20"           => "Epsom salt - Magnesiumsulfate-Heptahydrate",
        "C6H6MgO7"             => "Magnesiumhydrogencitrat",
        "C12H10Mg3O14"         => "Tri-Magnesiumdicitrat",
        "Canna Mono Magnesium" => "Canna Mono Magnesium",
        "Canna Mono Calcium"   => "Canna Mono Calcium",
        "CaC2H3O2H2O"          => "Calciumacetat",
        "Ca3C6H5O74H2O"        => "Calciumcitrat",
        "CaCO3"                => "Calciumcarbonat",
        "MgCO3"                => "Magnesiumcarbonat",
        "CaS04"                => "Calcium sulfate - Anhydrous",
        "CaS042H2O"            => "Calcium sulfate dihydrate",
        "CaNO3"                => "Calcium nitrate",
        "CaNO34H2O"            => "Calcium nitrate tetrahydrate",
        "Action_Gartenkalk"    => "Garden lime (Action)",
        "SR_Organics_CalMag_Boost" => "S&R Organics Vegan CalMag Boost",
        "MgO"                  => "Magnesiumoxid",
        "CaO"                  => "Calciumoxid",
        "custom_calcium"       => "Custom calcium additive",
        "custom_magnesium"     => "Custom magnesium additive",
    ],
    "content"  => [
        "calculator" => [
            "title"       => "CalMag Calculator",
            "description" => "With the CalMag Calculator you can calculate the need for calcium and magnesium for your plants.",
            "water"       => [
                "label" => "Initial water values",
            ],
            "state"       => [
                "propagation" => "Propagation",
                "vegetation"  => "Vegetation",
                "flower"      => "Flowering",
                "late_flower" => "Late flowering",
            ],
            "missing"     => ":name deficiency of :value mg/L",
            "result"      => [
                "title"              => "CalMag calculation results",
                "suggested_additive" => "Alternative fertilizer replacement",
                "deficiency"         => [
                    "magnesium"                    => "Your water has a magnesium deficiency of :ratio. This can be compensated by using :fertilizer and :magnesium_additive. A possible dosage can be found in the phase-dependent results below.",
                    "calcium"                      => "Your water has a calcium deficiency of :ratio. This can be partially compensated by using :fertilizer and :calcium_additive. A possible dosage can be found in the phase-dependent results below.",
                    "state"                        => [
                        "calcium_and_magnesium_missing" => 'Your water is missing <span class="text-red-500 font-bold">:calcium mg/L calcium</span> and <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span>.',
                        "calcium_missing"               => 'Your water is missing <span class="text-red-500 font-bold">:calcium mg/L calcium</span>.',
                        "magnesium_missing"             => 'Your water is missing <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span>.',
                        "calcium_and_magnesium_high"    => 'Your water contains <span class="text-red-500 font-bold">:calcium mg/L calcium</span> and <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span> too much.',
                        "calcium_high"                  => 'Your water contains <span class="text-red-500 font-bold">:calcium mg/L calcium</span> too much.',
                        "magnesium_high"                => 'Your water contains <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span> too much.',
                        "magnesium_and_calcium_ok"      => 'Your water is perfect and contains enough calcium and magnesium.',

                        "calcium_and_magnesium_missing_with_all"                              => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>, <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span> and <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span>.',
                        "calcium_and_magnesium_missing_with_all_without_calcium"              => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> and <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span>.',
                        "calcium_and_magnesium_missing_with_all_additives"                    => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span> and <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span>.',
                        "calcium_and_magnesium_missing_with_magnesium_additive"               => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span>.',
                        "calcium_and_magnesium_missing_with_calcium_additive"                 => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span>.',
                        "calcium_and_magnesium_missing_without_additive"                      => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>.',
                        "calcium_high_with_all_additives"                                     => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>, <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span> and <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span>.',
                        "calcium_high_with_all_without_calcium"                               => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> and <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span>.',
                        "calcium_high_with_all_without_magnesium"                             => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> and <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span>.',
                        "calcium_high_with_additive"                                          => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span> and <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span>.',
                        "calcium_high_with_magnesium_additive"                                => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span>.',
                        "calcium_high_with_calcium_additive"                                  => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span>.',
                        "calcium_high_without_additive"                                       => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>.',
                        "calcium_and_magnesium_ok_with_all_additives_and_fertilizer"          => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>, <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span> and <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span> to correct or prevent any deficiencies.',
                        "calcium_and_magnesium_ok_with_magnesium_additive_and_fertilizer"     => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span> and <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> to correct or prevent any deficiencies.',
                        "calcium_and_magnesium_ok_with_calcium_additive_and_fertilizer"       => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span> and <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> to correct or prevent any deficiencies.',
                        "calcium_and_magnesium_ok_with_magnesium_additive_without_fertilizer" => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L of a :magnesium_additive_concentration% :magnesium_additive_name solution</span> to correct or prevent any deficiencies.',
                        "calcium_and_magnesium_ok_with_calcium_additive_without_fertilizer"   => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:calcium_additive_ml ml/L of a :calcium_additive_concentration% :calcium_additive_name solution</span> to correct or prevent any deficiencies.',
                        "calcium_and_magnesium_ok_with_fertilizer_without_additive"           => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> to correct or prevent any deficiencies.',

                        "no_action_needed"       => 'An addition of calcium and magnesium is not necessary.',
                        "suggested_amount"       => 'The commonly recommended amount of <span class="text-red-500 font-bold">calcium (:calcium mg/L)</span> and <span class="text-red-500 font-bold">magnesium (:magnesium mg/L)</span> is not significantly exceeded - but cannot necessarily be avoided in extreme cases.',
                        "suggested_amount_table" => 'For <span class="text-red-500 font-bold">:volume</span> liters of water you need:',
                    ],
                    "dilution"                     => "Your water should be diluted to avoid exceeding the recommended limits. Mix :dilution of your water with :water distilled water.",
                    "none"                         => "Your water contains enough calcium and magnesium.",
                    "magnesium_without_fertilizer" => "Your water has a magnesium deficiency of :ratio. This can be compensated by using :magnesium_additive and :calcium_additive. A possible dosage can be found in the phase-dependent results below.",
                ],
                "share_link"         => "Share your result or save the following link to perform the same calculation again later:",
                "alternatives"       => [
                    "title"       => "Alternative products",
                    "description" => "The following products can be used as an alternative to the selected fertilizer.",
                    "fertilizer"  => [
                        "title"       => "Main fertilizer",
                        "description" => "The main fertilizer should usually be the fertilizer that is used the most and contains both calcium and magnesium.",
                        "brand"       => [
                            "name" => "Brand",
                            "url"  => "Brand URL",
                        ],
                        "product"     => [
                            "name"    => "Product",
                            "url"     => "Product URL",
                            "density" => "Density",
                            "ph"      => "pH",
                        ],
                        "elements"    => "Ingredients",
                        "try_link"    => "Try calculation with this fertilizer",
                    ],
                    "additive"    => [
                        "calcium"   => [
                            "title"       => "Calcium additive",
                            "description" => "The calcium additive should usually be a preparation that contains only calcium.",
                        ],
                        "magnesium" => [
                            "title"       => "Magnesium additive",
                            "description" => "The magnesium additive should usually be a preparation that contains only magnesium.",
                        ],
                        "name"      => "Name",
                        "density"   => "Density",
                        "elements"  => "Ingredients",
                        "try_link"  => "Try calculation with this additive",
                    ]
                ],
            ],
            "google"      => [
                "query" => "Postal code+water values",
            ],
            "button"      => [
                "search_plz"         => "Search water values online.",
                "calculate"          => "Calculate result",
                "table_show_details" => "Show details",
                "table_hide_details" => "Hide details",
                "regular"            => "Regular Calculator",
                "expert"             => "Extended Calculator",
                "compare"            => "Comparison Fertilizer",
                "wizard"             => "Wizard",
                "builder"            => "CalMag Builder",
            ],
            "table"       => [
                "phase"    => "Phase",
                "week"     => "Week",
                "ratio"    => "Calcium / Magnesium ratio",
                "target"   => "Target value",
                "missing"  => "Deficiency",
                "elements" => "Deficiency after adjustment",
                "volume"   => "For :volume liters of water you need:",

                "before"      => "Initial values",
                "adjustment"  => "Adjustment",
                "mg_additive" => "Magnesium additive",
                "ca_additive" => "Calcium additive",
                "fertilizer"  => "Fertilizer",
                "deficiency"  => "Deficiency",
                "final"       => "Total",
            ]
        ],
        "comparator" => [
            "result" => [
                "title" => "Comparison results",
            ]
        ],
        "form"       => [
            "fertilizer"             => [
                "label"       => "Fertilizer",
                "description" => "Select a fertilizer to be added to your water. It is assumed that the fertilizer is a solution with the concentration given in parentheses.",
                "option"      => [
                    "none" => "No fertilizer used",
                ],
                "element"     => [
                    "description" => "Please use %/ml as indicated on the packaging. If necessary, the density of the preparation must be taken into account.",
                ],
                "custom"      => [
                    "label" => "Fertilizer",
                ]
            ],
            "additive"               => [
                "magnesium" => [
                    "label"       => "Magnesium additive",
                    "description" => "Select an additive to be added to your water to increase the magnesium content, if the fertilizer does not contain enough magnesium.",
                ],
                "calcium"   => [
                    "label"       => "Calcium additive",
                    "description" => "Select an additive to be added to your water to increase the calcium content, if the fertilizer does not contain enough calcium.",
                ],
                "none"      => "No additive used",
            ],
            "additive_concentration" => [
                "label"       => "Concentration in %",
                "description" => "Enter the concentration of the additive here. The concentration is used to calculate the amount of additive to be added.",
            ],
            "ratio"                  => [
                "label"       => "Calcium / Magnesium ratio",
                "description" => "The ratio of calcium to magnesium should ideally be 3.5. However, this ratio can also be adjusted as desired.",
            ],
            "target_offset"          => [
                // Maximale Abweichung
                "label"       => "Maximal deviation",
                "description" => "The offset indicates by how much percent the values should deviate from the target value. This value is given in percent.",
            ],
            "target_weeks"           => [
                "label"       => "Weeks",
                "description" => "Select the number of weeks for which the target values should be calculated.",
            ],
            "target_week"            => [
                "label" => "Week",
            ],
            "target_model"           => [
                // Dünge-Schema
                "label"       => "Model",
                // Wähle hier das Dünge-Schema aus, welches du verwenden möchtest. Ob linear oder dynamisch (Ca, Mg, Ca/Mg) bleibt dir und deinen Präferenzen überlassen.
                "description" => "Select the model you want to use. Whether linear or dynamic (Ca, Mg, Ca/Mg) is up to you and your preferences.",
            ],
            "target_calcium"         => [
                "label"       => "Calcium",
                "description" => "The target value for calcium indicates how much calcium should be present in your water. This value is given in mg/L.",
            ],
            "target_magnesium"       => [
                "label"       => "Magnesium",
                "description" => "The target value for magnesium indicates how much magnesium should be present in your water. This value is given in mg/L.",
            ],
            "density"                => [
                "label"       => "Density",
                "description" => "Enter the density of your fertilizer here. The density is used to calculate the amount of fertilizer to be added.",
            ],
            "ml"                     => [
                "label" => "Milliliters",
            ],
            "state"                  => [
                "label" => "Phase",
            ],
            "dilution"               => [
                "label" => "Dilution",
            ],
            "support_dilution"       => [
                "label"       => "Dilution with distilled water",
                // Wenn dein Wasser zu hohe Werte aufweist, kann es sinnvoll sein, das Wasser zu verdünnen.
                "description" => "If your water has too high values, it may be useful to dilute the water.",
            ],
            "volume"                 => [
                "label"       => "Water volume in L",
                "description" => "Enter the volume of your water here. The volume is used to calculate the amount of fertilizer and additive to be added.",
            ],
            "region"                 => [
                "label"       => "Region",
                "description" => "Select your region here. The region has no influence on the calculation, but is only used to display the correct links.",
            ],
            "elements"               => [
                "title"       => "Your water values",
                "description" => "Enter the values of your tap water here. These values can usually be found on the water bill or the water report. If the values are not known, they can also be determined via the Google search. Simply click on the following link and search for your postal code and the water values:",
            ],
            "element"                => [
                "calcium"     => [
                    "label" => "Calcium",
                    "cao"   => [
                        "label"       => "Calcium oxide",
                        // Angaben in % wie auf der Verpackung angegeben. Ggf. muss die Dichte des Präparates berücksichtigt werden.
                        "description" => "Please use %/ml as indicated on the packaging. If necessary, the density of the preparation must be taken into account.",
                    ],
                ],
                "magnesium"   => [
                    "label" => "Magnesium",
                    "mgo"   => [
                        "label"       => "Magnesium oxide",
                        "description" => "Please use %/ml as indicated on the packaging. If necessary, the density of the preparation must be taken into account.",
                    ],
                ],
                "potassium"   => [
                    "label" => "Potassium",
                ],
                "iron"        => [
                    "label" => "Iron",
                ],
                "manganese"   => [
                    "label" => "Manganese",
                ],
                "zinc"        => [
                    "label" => "Zink",
                ],
                "sulphate"    => [
                    "label" => "Sulfate",
                ],
                "nitrate"     => [
                    "label" => "Nitrate",
                ],
                "nitrite"     => [
                    "label" => "Nitrite",
                ],
                "sulfur"      => [
                    "label" => "Sulfur",
                ],
                "nitrogen"    => [
                    "label" => "Nitrogen",
                ],
                "humic_acids" => [
                    "label" => "Humic acids",
                ],
                "boron"       => [
                    "label" => "Boron",
                ],
                "chloride"    => [
                    "label" => "Chloride",
                ],
            ],
        ],
        "wizard" => [
            "step" => [
                "water" => [
                    "title" => "Select Your Water Source",
                    "description" => "Your water source is crucial as it determines the base mineral content of your solution. Different water sources contain varying levels of calcium, magnesium, and other minerals.",
                    "help" => [
                        "title" => "Understanding Water Sources",
                        "description" => "Different water sources have different properties:",
                        "sources" => [
                            "ro" => "RO Water: Virtually mineral-free, perfect base for precise nutrient control",
                            "rain" => "Rain Water: Generally low in minerals, may vary by region and collection method",
                            "tap" => "Tap Water: Mineral content varies by location, check local water reports",
                            "well" => "Well Water: Can have high mineral content, testing recommended"
                        ]
                    ],
                    "source" => [
                        "label" => "Water Source",
                        "description" => "Select your primary water source. This will help determine if additional mineral content needs to be considered.",
                        "ro" => "Reverse Osmosis (RO) Water",
                        "rain" => "Rain Water",
                        "tap" => "Tap Water",
                        "well" => "Well Water"
                    ],
                    "minerals" => [
                        "title" => "Water Mineral Content",
                        "description" => "Please provide the mineral content of your water if known. You can usually find this information from your water supplier or through a water test.",
                        "help" => [
                            "title" => "Don't know your water's mineral content?",
                            "tips" => [
                                "Check your water supplier's website for a water quality report",
                                "Search online using your postal code + \"water quality report\" or \"water analysis\"",
                                "Use a water testing kit from your local garden center or hydroponics store",
                                "Contact your water supplier directly for detailed information",
                                "Consider professional water testing services for accurate results",
                                "Check your latest water bill - it may include mineral content information",
                                "Join local gardening groups - members often share water analysis data",
                                "Use online water quality databases specific to your region"
                            ],
                            "search" => [
                                "title" => "Search Online",
                                "description" => "Click below to search for your water quality report:",
                                "button" => "Search Water Analysis"
                            ]
                        ]
                    ]
                ],
                "fertilizer" => [
                    "title" => "Choose Your Fertilizer",
                    "description" => "The fertilizer choice is crucial as it determines the base ratio of calcium to magnesium in your solution. Select a pre-configured fertilizer or choose \"No fertilizer used\" to create a custom mix.",
                    "help" => [
                        "title" => "Consider these factors when choosing your fertilizer:",
                        "factors" => [
                            "ratio" => "Pre-mixed fertilizers often have specific Ca:Mg ratios",
                            "uptake" => "The ratio affects nutrient availability and plant uptake",
                            "stage" => "Choose based on your plant's growth stage and needs",
                            "custom" => "Consider selecting \"No fertilizer\" for custom additive mixing"
                        ]
                    ]
                ],
                "additives" => [
                    "title" => "Select Additives",
                    "description" => "Additives help fine-tune your nutrient solution. Choose calcium and magnesium additives and their concentrations to achieve the perfect balance for your plants.",
                    "help" => [
                        "title" => "Tips for additive selection and concentration:",
                        "tips" => [
                            "concentration_high" => "Higher concentrations mean less volume needed but require more precise measuring",
                            "concentration_low" => "Lower concentrations are more forgiving but require larger volumes",
                            "solubility" => "Consider solubility limits when setting concentrations",
                            "ph" => "Some additives may affect pH - check product documentation"
                        ]
                    ]
                ],
                "settings" => [
                    "title" => "Final Settings",
                    "description" => "Configure the final parameters for your calculation, including the target ratio, solution volume, and calculation model.",
                    "help" => [
                        "title" => "Understanding the settings:",
                        "settings" => [
                            "ratio" => "Ca:Mg Ratio: 3.5:1 is common, but may vary by plant type and growth stage",
                            "volume" => "Volume: Calculate total solution needed for your system",
                            "models" => [
                                "title" => "Model Selection:",
                                "linear" => "Linear/Fumu: Consistent ratios throughout growth",
                                "ppp_ca" => "PPP-Ca: Optimized for calcium-heavy feeding",
                                "ppp_mg" => "PPP-Mg: Optimized for magnesium-heavy feeding",
                                "ppp_ca_mg" => "PPP-Ca/Mg: Dynamic ratio adjustment"
                            ],
                            "dilution" => "Dilution Support: Enable if your water has high mineral content"
                        ]
                    ]
                ]
            ],
            "navigation" => [
                "next" => "Next Step",
                "previous" => "Previous",
                "calculate" => "Calculate Results",
                "steps" => [
                    "water" => "1. Water Source",
                    "fertilizer" => "2. Fertilizer",
                    "additives" => "3. Additives",
                    "settings" => "4. Settings"
                ]
            ]
        ]
    ]
];
