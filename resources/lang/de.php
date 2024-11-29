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
        "default" => "de",
        "option"  => [
            "us" => "Vereinigte Staaten von Amerika",
            "de" => "Deutschland",
        ]
    ],
    "header"   => [
        "logo" => [
            "appendix" => "Rechner"
        ]
    ],
    "footer"   => [
        "impress" => "Impressum & Kontakt",
    ],
    "dilution" => [
        "stock" => "Dein Wasser",
        "water" => "Osmosewasser",
        "label" => "Verdünnungsverhältnis",
    ],
    "additive" => [
        "MgSO4"                => "Bittersalz",
        "C6H6MgO7"             => "Magnesiumhydrogencitrat",
        "C12H10Mg3O14"         => "Tri-Magnesiumdicitrat",
        "Canna Mono Magnesium" => "Canna Mono Magnesium",
        "Canna Mono Calcium"   => "Canna Mono Calcium",
        "CaC2H3O2H2O"          => "Calciumacetat",
        "Ca3C6H5O74H2O"        => "Calciumcitrat",
        "CaCO3"                => "Calciumcarbonat",
        "MgCO3"                => "Magnesiumcarbonat",
        "Action_Gartenkalk"  => "Gartenkalk (Green Boots - Action)",
        "MgO"                  => "Magnesiumoxid",
        "CaO"                  => "Calciumoxid",
    ],
    "content"  => [
        "calculator" => [
            "title"       => "CalMag Rechner",
            "description" => "Mit dem CalMag Rechner können Sie den Bedarf an Calcium und Magnesium für Ihre Pflanzen berechnen.",
            "water"       => [
                "label" => "Ausgangswasserwerte",
            ],
            "state"       => [
                "propagation" => "Anzucht",
                "vegetation"  => "Wachstumsphase",
                "flower"      => "Blütephase",
                "late_flower" => "Späte Blütephase",
            ],
            "missing"     => ":name Defizit von :value mg/L",
            "result"      => [
                "title"      => "Ergebnisse der CalMag-Berechnung",
                "suggested_additive" => "Alternativer Präparatersatz",
                "deficiency" => [
                    "magnesium" => "Dein Wasser weist ein Magnesium-Defizit von :ratio auf. Dieser kann durch die Verwendung von :fertilizer und :magnesium_additive ausgeglichen werden. Eine mögliche Dosierung findest du in den Phasenabhängigen Ergebnissen unterhalb.",
                    "calcium"   => "Dein Wasser weist ein Calcium-Defizit von :ratio auf. Dieser kann durch die Verwendung von :fertilizer und :calcium_additive ausgeglichen werden. Eine mögliche Dosierung findest du in den Phasenabhängigen Ergebnissen unterhalb.",
                    "state"     => [
                        "calcium_and_magnesium_missing" => 'Deinem Wasser fehlen <span class="text-red-500 font-bold">:calcium mg/L Calcium</span> und <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span>.',
                        "calcium_missing"               => 'Deinem Wasser fehlen <span class="text-red-500 font-bold">:calcium mg/L Calcium</span>.',
                        "magnesium_missing"             => 'Deinem Wasser fehlen <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span>.',
                        "calcium_and_magnesium_high"    => 'Dein Wasser enthält <span class="text-red-500 font-bold">:calcium mg/L Calcium</span> und <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span> zu viel.',
                        "calcium_high"                  => 'Dein Wasser enthält <span class="text-red-500 font-bold">:calcium mg/L Calcium</span> zu viel.',
                        "magnesium_high"                => 'Dein Wasser enthält <span class="text-red-500 font-bold">:magnesium mg/L Magnesium</span> zu viel.',
                        "magnesium_and_calcium_ok"      => 'Dein Wasser ist perfekt und enthält ausreichend Calcium und Magnesium.',

                        "calcium_and_magnesium_missing_with_all"                              => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>, <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> und <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> ausgleichen.',
                        "calcium_and_magnesium_missing_with_all_without_calcium"              => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> ausgleichen.',
                        "calcium_and_magnesium_missing_with_all_additives"                    => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> und <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> ausgleichen.',
                        "calcium_and_magnesium_missing_with_magnesium_additive"               => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> ausgleichen.',
                        "calcium_and_magnesium_missing_with_calcium_additive"                 => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> ausgleichen.',
                        "calcium_and_magnesium_missing_without_additive"                      => 'Diesen Mangel kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> ausgleichen.',
                        "calcium_high_with_all_additives"                                     => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>, <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> und <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> teilweise ausgleichen.',
                        "calcium_high_with_all_without_calcium"                               => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> teilweise ausgleichen.',
                        "calcium_high_with_all_without_magnesium"                             => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> teilweise ausgleichen.',
                        "calcium_high_with_additive"                                          => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> und <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> teilweise ausgleichen.',
                        "calcium_high_with_magnesium_additive"                                => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> teilweise ausgleichen.',
                        "calcium_high_with_calcium_additive"                                  => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> teilweise ausgleichen.',
                        "calcium_high_without_additive"                                       => 'Diesen Überschuss kannst du mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> teilweise ausgleichen.',
                        "calcium_and_magnesium_ok_with_all_additives_and_fertilizer"          => 'Dein Wasser enthält bereits genügend Calcium und Magnesium.<br />Du könntest dein Wasser mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span>, <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> und <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',
                        "calcium_and_magnesium_ok_with_magnesium_additive_and_fertilizer"     => 'Dein Wasser enthält bereits genügend Calcium und Magnesium.<br />Du könntest dein Wasser mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',
                        "calcium_and_magnesium_ok_with_calcium_additive_and_fertilizer"       => 'Dein Wasser enthält bereits genügend Calcium und Magnesium.<br />Du könntest dein Wasser mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> und <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',
                        "calcium_and_magnesium_ok_with_magnesium_additive_without_fertilizer" => 'Dein Wasser enthält bereits genügend Calcium und Magnesium. <br /> Du könntest dein Wasser mit <span class="text-red-500 font-bold">:magnesium_additive_ml ml/L einer :magnesium_additive_concentration% :magnesium_additive_name Lösung</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',
                        "calcium_and_magnesium_ok_with_calcium_additive_without_fertilizer"   => 'Dein Wasser enthält bereits genügend Calcium und Magnesium. <br /> Du könntest dein Wasser mit <span class="text-red-500 font-bold">:calcium_additive_ml ml/L einer :calcium_additive_concentration% :calcium_additive_name Lösung</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',
                        "calcium_and_magnesium_ok_with_fertilizer_without_additive"           => 'Dein Wasser enthält bereits genügend Calcium und Magnesium. <br /> Du könntest dein Wasser mit <span class="text-red-500 font-bold">:fertilizer_ml ml/L :fertilizer_name</span> aufbereiten um etwaige Mangelerscheinungen zu beheben bzw ihnen vorzubeugen.',

                        "no_action_needed"       => 'Eine Zugabe von Calcium und Magnesium ist nicht notwendig.',
                        "suggested_amount"       => 'Die häufig empfohlene Menge an <span class="text-red-500 font-bold">Calcium (:calcium mg/L)</span> und <span class="text-red-500 font-bold">Magnesium (:magnesium mg/L)</span> wird dabei möglichst nicht nennenswert überschritten - lässt sich in extremen Fällen aber nicht unbedingt vermeiden.',
                        "suggested_amount_table" => 'Zur Herstellung von <span class="text-red-500 font-bold">:volume</span> Litern:',
                    ],
                    "dilution"  => "Dein Wasser sollte verdünnt werden, um die Empfohlenen Grenzwerte nicht zu überschreiten. Mische hierfür :dilution deines Wassers mit :water destilliertem Wasser.",
                    "none"      => "Dein Wasser enthält ausreichend Calcium und Magnesium.",
                    "magnesium_without_fertilizer" => "Dein Wasser weist ein Magnesium-Defizit von :ratio auf. Dieser kann durch die Verwendung von :magnesium_additive und :calcium_additive ausgeglichen werden. Eine mögliche Dosierung findest du in den Phasenabhängigen Ergebnissen unterhalb.",
                ],
                "share_link" => "Teile dein Ergebnis oder speichere dir folgenden Link, um dieselbe Berechnung später erneut durchzuführen:",
                "alternatives" => [
                    "title" => "Alternativer Präparatersatz",
                    "description" => "Sollte das gewählte Präparat nicht verfügbar sein, können folgende Alternativen verwendet werden:",
                    "fertilizer" => [
                        "title" => "Hauptpräparat",
                        "description" => "Das Hauptpräparat sollte in der Regel das Präparat sein, welches am meisten verwendet wird und sowohl Calcium als auch Magnesium enthält.",
                        "brand" => [
                            "name" => "Hersteller",
                            "url" => "Hersteller-URL",
                        ],
                        "product" => [
                            "name" => "Produkt",
                            "url" => "Produkt-URL",
                            "density" => "Dichte",
                            "ph" => "pH-Wert",
                        ],
                        "elements" => "Inhaltsstoffe",
                        "try_link" => "Berechnung mit diesem Präparat versuchen",
                    ],
                    "additive" => [
                        "calcium" => [
                            "title" => "Calcium-Zusatz",
                            "description" => "Der Calcium-Zusatz sollte in der Regel ein Präparat sein, welches nur Calcium enthält.",
                        ],
                        "magnesium" => [
                            "title" => "Magnesium-Zusatz",
                            "description" => "Der Magnesium-Zusatz sollte in der Regel ein Präparat sein, welches nur Magnesium enthält.",
                        ],
                        "name" => "Bezeichnung",
                        "density" => "Dichte",
                        "elements" => "Inhaltsstoffe",
                        "try_link" => "Berechnung mit diesem Zusatzstoff versuchen",
                    ]
                ]
            ],
            "google"      => [
                "query" => "Postleitzahl+Wasserwerte",
            ],
            "button"      => [
                "search_plz" => "Wasserwerte online suchen.",
                "calculate"  => "Ergebnis berechnen",
                "table_show_details" => "Inhaltsstoffdetails anzeigen",
                "table_hide_details" => "Inhaltsstoffdetails verbergen",
            ],
            "table" => [
                "phase" => "Phase",
                "week" => "Woche",
                "ratio" => "Calcium/Magnesium Verhältnis",
                "target" => "Inhaltsstoff-Zielwerte",
                "missing" => "Fehlende Inhaltsstoffe",
                "elements" => "Inhaltsstoffe nach Anpassung",
                "volume" => "Zur Herstellung von :volume Litern:",

                "before" => "Ausgangsinhaltsstoffe",
                "adjustment" => "Anpassung & Korrektur",
                "mg_additive" => "Magnesium Zusatz",
                "ca_additive" => "Calcium Zusatz",
                "fertilizer" => "Präparat",
                "deficiency" => "Defizit/Zugabe",
                "final" => "Gesamt",
            ]
        ],
        "form"       => [
            "fertilizer" => [
                "label"       => "Präparat",
                "description" => "Wähle das Präparat aus, welches du verwenden möchtest. Optimalerweise sollte ein Präparat zum passenden Verhältnis gewählt werden.",
                "option" => [
                    "none" => "Kein Präparat verwenden",
                ]
            ],
            "additive"   => [
                "magnesium" => [
                    "label"       => "Magnesium-Zusatzstoff",
                    "description" => "Wähle einen Zusatzstoff aus, welcher deinem Wasser hinzugefügt werden kann um den Magnesiumgehalt zu erhöhen, sollte das gewählte Präparat nicht ausreichen.",
                ],
                "calcium"   => [
                    "label"       => "Calcium-Zusatzstoff",
                    "description" => "Wähle einen Zusatzstoff aus, welcher deinem Wasser hinzugefügt werden kann um den Calciumgehalt zu erhöhen, sollte das gewählte Präparat nicht ausreichen.",
                ],
                "none"      => "Keinen Zusatzstoff verwenden",
            ],
            "additive_concentration" => [
                "label"       => "Konzentration in %",
                "description" => "Die Konzentration gibt an, wie viel des Zusatzstoffes (z.B. Bittersalz, oder Canna Mono) in der eigens hergestellten Lösung enthalten ist. Dieser Wert wird in Prozent angegeben. Z.B. 10% Bittersalz bedeutet, dass 10g Bittersalz in 90ml Wasser gelöst wurden.",
            ],
            "ratio"      => [
                "label"       => "Calcium / Magnesium - Verhältnis",
                "description" => "Das Verhältnis von Calcium zu Magnesium liegt oft bei 3.5. Dieses Verhältnis kann beliebig angepasst werden.",
            ],
            "density" => [
                "label" => "Dichte",
                "description" => "Die Dichte gibt an, wie schwer ein Stoff ist. Dieser Wert wird in g/cm³ angegeben.",
            ],
            "volume"     => [
                "label"       => "Volumen",
                "description" => "Das Volumen gibt an, wie viel Wasser behandelt werden soll. Dieser Wert wird in Litern angegeben.",
            ],
            "region"     => [
                "label"       => "Region",
                "description" => "Wähle hier deine Region aus. Die Region hat keinen Einfluss auf die Berechnung, sondern dient lediglich zur Anzeige der richtigen Links.",
            ],
            "elements"   => [
                "title"       => "Deine Wasserwerte",
                "description" => "Trage hier die Werte deines Leitungswassers ein. Diese Werte können in der Regel auf der Wasserrechnung oder dem Wasserbericht gefunden werden. Sollten die Werte nicht bekannt sein, können diese auch über die Google Suche ermittelt werden. Klicke dafür einfach auf den nachfolgenden Link und suche nach deiner Postleitzahl und den Wasserwerten:",
            ],
            "element"    => [
                "calcium"   => [
                    "label" => "Calcium",
                ],
                "magnesium" => [
                    "label" => "Magnesium",
                ],
                "potassium" => [
                    "label" => "Kalium",
                ],
                "iron"      => [
                    "label" => "Eisen",
                ],
                "sulphate"  => [
                    "label" => "Sulfat",
                ],
                "nitrate"   => [
                    "label" => "Nitrat",
                ],
                "manganese"   => [
                    "label" => "Manganese",
                ],
                "zinc"   => [
                    "label" => "Zink",
                ],
                "nitrite"   => [
                    "label" => "Nitrit",
                ],
                "sulfur"    => [
                    "label" => "Schwefel",
                ],
                "nitrogen"  => [
                    "label" => "Stickstoff",
                ],
                "humic_acids" => [
                    "label" => "Huminsäuren",
                ],
            ],
        ]
    ]
];