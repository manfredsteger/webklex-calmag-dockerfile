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
    "region" => [
        "default" => "us",
        "option" => [
            "us" => "United States of America",
            "de" => "Germany",
        ]
    ],
    "header" => [
        "logo" => [
            "appendix" => "Calculator",
        ]
    ],
    "footer" => [
        "impress" => "Impress & Contact",
    ],
    "dilution" => [
        "stock" => "Your water",
        "water" => "Distilled water",
    ],
    "additive" => [
        "MgSO4" => "Magnesiumsulfat",
        "C6H6MgO7" => "Magnesiumhydrogencitrat",
        "C12H10Mg3O14" => "Tri-Magnesiumdicitrat",
        "Canna Mono" => "Canna Mono Magnesium",
    ],
    "content" => [
        "calculator" => [
            "title" => "CalMag Calculator",
            "description" => "With the CalMag Calculator you can calculate the need for calcium and magnesium for your plants.",
            "water" => [
                "label" => "Initial water values",
            ],
            "state" => [
                "propagation" => "Propagation",
                "vegetation" => "Vegetation",
                "flower" => "Flowering",
                "late_flower" => "Late flowering",
            ],
            "missing" => ":name deficiency of :value mg/L",
            "result" => [
                "title" => "CalMag calculation results",
                "deficiency" => [
                    "magnesium" => "Your water has a magnesium deficiency of :ratio. This can be compensated by using :fertilizer and :additive. A possible dosage can be found in the phase-dependent results below.",
                    "calcium" => "Your water has a calcium deficiency of :ratio. This can be partially compensated by using :fertilizer. A possible dosage can be found in the phase-dependent results below.",
                    "state" => [
                        "calcium_and_magnesium_missing" => 'Your water is missing <span class="text-red-500 font-bold">:calcium mg/L calcium</span> and <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span>.',
                        "calcium_missing" => 'Your water is missing <span class="text-red-500 font-bold">:calcium mg/L calcium</span>.',
                        "magnesium_missing" => 'Your water is missing <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span>.',
                        "calcium_and_magnesium_high" => 'Your water contains <span class="text-red-500 font-bold">:calcium mg/L calcium</span> and <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span> too much.',
                        "calcium_high" => 'Your water contains <span class="text-red-500 font-bold">:calcium mg/L calcium</span> too much.',
                        "magnesium_high" => 'Your water contains <span class="text-red-500 font-bold">:magnesium mg/L magnesium</span> too much.',
                        "magnesium_and_calcium_ok" => 'Your water is perfect and contains enough calcium and magnesium.',

                        "calcium_and_magnesium_missing_with_all" => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> and <span class="text-red-500 font-bold">:additive_ml ml/L of a :additive_concentration% :additive_name solution</span>.',
                        "calcium_and_magnesium_missing_with_additive" => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:additive_ml ml/L of a :additive_concentration% :additive_name solution</span>.',
                        "calcium_and_magnesium_missing_without_additive" => 'You can compensate for this deficiency with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>.',
                        "calcium_high_with_all" => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> and <span class="text-red-500 font-bold">:additive_ml ml/L of a :additive_concentration% :additive_name solution</span>.',
                        "calcium_high_with_additive" => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:additive_ml ml/L of a :additive_concentration% :additive_name solution</span>.',
                        "calcium_high_without_additive" => 'You can partially compensate for this excess with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>.',
                        "calcium_and_magnesium_ok_with_additive_and_fertilizer" => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:additive_ml ml/L of a :additive_concentration% :additive_name solution</span> and <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> to correct or prevent any deficiencies.',
                        "calcium_and_magnesium_ok_with_additive_without_fertilizer" => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:additive_ml ml/L of a :additive_concentration% :additive_name solution</span> to correct or prevent any deficiencies.',
                        "calcium_and_magnesium_ok_with_fertilizer_without_additive" => 'Your water already contains enough calcium and magnesium. <br /> You could prepare your water with <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> to correct or prevent any deficiencies.',

                        "no_action_needed" => 'An addition of calcium and magnesium is not necessary.',
                        "suggested_amount" => 'The commonly recommended amount of <span class="text-red-500 font-bold">calcium (:calcium mg/L)</span> and <span class="text-red-500 font-bold">magnesium (:magnesium mg/L)</span> is not significantly exceeded - but cannot necessarily be avoided in extreme cases.',
                        "suggested_amount_table" => 'For <span class="text-red-500 font-bold">:volume</span> liters of water you need:',
                    ],
                    "dilution" => "Your water should be diluted to avoid exceeding the recommended limits. Mix :dilution of your water with :water distilled water.",
                ],
                "share_link" => "Share your result or save the following link to perform the same calculation again later:",
            ],
            "google" => [
                "query" => "Postal code+water values",
            ],
            "button" => [
                "search_plz" => "Search water values online.",
                "calculate" => "Calculate result",
            ]
        ],
        "form" => [
            "fertilizer" => [
                "label" => "Fertilizer",
                "description" => "Select a fertilizer to be added to your water. It is assumed that the fertilizer is a solution with the concentration given in parentheses.",
            ],
            "additive" => [
                "label" => "Additive",
                "description" => "Select an additive to be added to your water. It is assumed that the additive is a solution with the concentration given in parentheses.",
            ],
            "ratio" => [
                "label" => "Calcium to Magnesium ratio",
                "description" => "The ratio of calcium to magnesium should ideally be 3.5. However, this ratio can also be adjusted as desired.",
            ],
            "volume" => [
                "label" => "Volume",
                "description" => "Enter the volume of your water here. The volume is used to calculate the amount of fertilizer and additive to be added.",
            ],
            "region" => [
                "label" => "Region",
                "description" => "Select your region here. The region has no influence on the calculation, but is only used to display the correct links.",
            ],
            "elements" => [
                "title" => "Your water values",
                "description" => "Enter the values of your tap water here. These values can usually be found on the water bill or the water report. If the values are not known, they can also be determined via the Google search. Simply click on the following link and search for your postal code and the water values:",
            ],
            "element" => [
                "calcium" => [
                    "label" => "Calcium",
                ],
                "magnesium" => [
                    "label" => "Magnesium",
                ],
                "potassium" => [
                    "label" => "Potassium",
                ],
                "iron" => [
                    "label" => "Iron",
                ],
                "sulphate" => [
                    "label" => "Sulfate",
                ],
                "nitrate" => [
                    "label" => "Nitrate",
                ],
                "nitrite" => [
                    "label" => "Nitrite",
                ],
                "sulfur" => [
                    "label" => "Sulfur",
                ],
                "nitrogen" => [
                    "label" => "Nitrogen",
                ],
            ],
        ]
    ]
];