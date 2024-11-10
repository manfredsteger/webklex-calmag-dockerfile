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
        "default" => "de",
        "option" => [
            "us" => "Vereinigte Staaten von Amerika",
            "de" => "Deutschland",
        ]
    ],
    "header" => [
        "logo" => [
            "appendix" => "Rechner"
        ]
    ],
    "footer" => [
        "impress" => "Impressum & Kontakt",
    ],
    "content" => [
        "calculator" => [
            "title" => "CalMag Rechner",
            "description" => "Mit dem CalMag Rechner können Sie den Bedarf an Calcium und Magnesium für Ihre Pflanzen berechnen.",
            "water" => [
                "label" => "Ausgangswasserwerte",
            ],
            "state" => [
                "propagation" => "Anzucht & Vermehrung",
                "vegetation" => "Wachstumsphase",
                "flower" => "Blütephase",
                "late_flower" => "Späte Blütephase",
            ],
            "missing" => ":name Defizit von :value mg/L",
            "result" => [
                "title" => "Ergebnisse der CalMag-Berechnung",
                "deficiency" => [
                    "magnesium" => "Dein Wasser weist ein Magnesium-Defizit von :ratio auf. Dieser kann durch die Verwendung von :fertilizer und :additive ausgeglichen werden. Eine mögliche Dosierung findest du in den Phasenabhängigen Ergebnissen unterhalb.",
                    "calcium" => "Dein Wasser weist ein Calcium-Defizit von :ratio auf. Dieser kann durch die Verwendung von :fertilizer teilweise ausgeglichen werden. Eine mögliche Dosierung findest du in den Phasenabhängigen Ergebnissen unterhalb.",
                    "state" => [
                        "calcium_and_magnesium_missing" => 'Deinem Wasser fehlen <span class="text-red-500 font-bold">:calcium mg/L Calcium</span> und <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span>.',
                        "calcium_missing" => 'Deinem Wasser fehlen <span class="text-red-500 font-bold">:calcium mg/L Calcium</span>.',
                        "magnesium_missing" => 'Deinem Wasser fehlen <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span>.',
                        "calcium_and_magnesium_high" => 'Dein Wasser enthält <span class="text-red-500 font-bold">:calcium mg/L Calcium</span> und <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span> zu viel.',
                        "calcium_high" => 'Dein Wasser enthält <span class="text-red-500 font-bold">:calcium mg/L Calcium</span> zu viel.',
                        "magnesium_high" => 'Dein Wasser enthält <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span> zu viel.',
                        "magnesium_and_calcium_ok" => 'Dein Wasser ist perfekt und enthält ausreichend Calcium und Magnesium.',

                        "calcium_and_magnesium_missing_with_additive" => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:additive_ml ml/L einer :additive_concentration% :additive_name Lösung</span> ausgleichen.',
                        "calcium_and_magnesium_missing_without_additive" => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> ausgleichen.',
                        "calcium_high_with_additive" => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:additive_ml ml/L einer :additive_concentration% :additive_name Lösung</span> teilweise ausgleichen.',
                        "calcium_high_without_additive" => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> teilweise ausgleichen.',
                        "calcium_and_magnesium_ok_with_additive_and_fertilizer" => 'Dein Wasser enthält bereits genügend Calcium und Magnesium.<br />Du könntest dein Wasser mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:additive_ml ml/L einer :additive_concentration% :additive_name Lösung</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',
                        "calcium_and_magnesium_ok_with_additive_without_fertilizer" => 'Dein Wasser enthält bereits genügend Calcium und Magnesium. <br /> Du könntest dein Wasser mit <span class="text-red-500 font-bold">:additive_ml ml/L einer :additive_concentration% :additive_name Lösung</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',
                        "calcium_and_magnesium_ok_with_fertilizer_without_additive" => 'Dein Wasser enthält bereits genügend Calcium und Magnesium. <br /> Du könntest dein Wasser mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',

                        "no_action_needed" => 'Eine Zugabe von Calcium und Magnesium ist nicht notwendig.',
                        "suggested_amount" => 'Die häufig empfohlene Menge an <span class="text-red-500 font-bold">Calcium (:calcium mg/L)</span> und <span class="text-red-500 font-bold">Magnesium (:magnesium mg/L)</span> wird dabei möglichst nicht nennenswert überschritten - lässt sich in extremen Fällen aber nicht unbedingt vermeiden.',
                    ],
                ]
            ],
            "google" => [
                "query" => "Postleitzahl+Wasserwerte",
            ],
            "button" => [
                "search_plz" => "Wasserwerte online suchen.",
                "calculate" => "Ergebnis berechnen",
            ]
        ],
        "form" => [
            "fertilizer" => [
                "label" => "Präparat",
                "description" => "Wähle das Präparat aus, welches du verwenden möchtest. Optimalerweise sollte ein Präparat zum passenden Verhältnis gewählt werden.",
            ],
            "additive" => [
                "label" => "Zusatzstoff",
                "description" => "Wähle einen Zusatzstoff aus, welcher deinem Wasser hinzugefügt werden soll. Es wird von einer Lösung mit der in Klammern angegebenen Konzentration ausgegangen.",
            ],
            "ratio" => [
                "label" => "Calcium / Magnesium - Verhältnis",
                "description" => "Das Verhältnis von Calcium zu Magnesium sollte idealerweise bei 3.5 liegen. Dieses Verhältnis kann aber auch beliebig angepasst werden.",
            ],
            "region" => [
                "label" => "Region",
                "description" => "Wähle hier deine Region aus. Die Region hat keinen Einfluss auf die Berechnung, sondern dient lediglich zur Anzeige der richtigen Links.",
            ],
            "elements" => [
                "title" => "Deine Wasserwerte",
                "description" => "Trage hier die Werte deines Leitungswassers ein. Diese Werte können in der Regel auf der Wasserrechnung oder dem Wasserbericht gefunden werden. Sollten die Werte nicht bekannt sein, können diese auch über die Google Suche ermittelt werden. Klicke dafür einfach auf den nachfolgenden Link und suche nach deiner Postleitzahl und den Wasserwerten:",
            ],
            "element" => [
                "calcium" => [
                    "label" => "Calcium",
                ],
                "magnesium" => [
                    "label" => "Magnesium",
                ],
                "potassium" => [
                    "label" => "Kalium",
                ],
                "iron" => [
                    "label" => "Eisen",
                ],
                "sulphate" => [
                    "label" => "Sulfat",
                ],
                "nitrate" => [
                    "label" => "Nitrat",
                ],
                "nitrite" => [
                    "label" => "Nitrit",
                ],
                "sulfur" => [
                    "label" => "Schwefel",
                ],
                "nitrogen" => [
                    "label" => "Stickstoff",
                ],
            ],
        ]
    ]
];