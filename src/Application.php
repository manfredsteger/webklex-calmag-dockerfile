<?php
/*
* File: Application.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 19:29
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\CalMag;

/**
 * Class Application
 *
 * @package Webklex\CalMag
 */
class Application {

    /**
     * @var string VERSION The current version of the application
     */
    const VERSION = "2.3.1";

    /**
     * @var Controller $controller The controller instance
     */
    protected Controller $controller;


    /**
     * Application constructor.
     */
    public function __construct() {
        $this->controller = new Controller();
    }

    /**
     * Route the request
     * @return void
     */
    public function route(): void {
        $payload = [];

        // Check if the current request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $_payload = $_GET["p"] ?? "";

            // Check if a shared payload is present
            if ($_payload !== "") {
                $_payload = base64_decode($_payload);
                $_payload = json_decode($_payload, true);
                if (is_array($_payload)) {
                    $payload = $_payload;
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check for potential json request and call the api if this is the case
            if ($_SERVER["HTTP_ACCEPT"] === "application/json" || $_SERVER["CONTENT_TYPE"] === "application/json") {
                $payload = json_decode(file_get_contents('php://input'), true);
                if (!is_array($payload)) {
                    $payload = [];
                }
                $this->controller->api($payload);
                return;

            }
            $payload = $_POST;
        }


        if (isset($_GET["compare"])) {
            $this->controller->compare($payload);
            return;
        }

        if (isset($_GET["builder"])) {
            $this->controller->builder($payload);
            return;
        }

        if (count($payload) > 0) {
            $this->controller->result([
                                          "fertilizer"             => $payload["fertilizer"] ?? "",
                                          "additive"               => $payload["additive"] ?? [],
                                          "ratio"                  => $payload["ratio"] ?? 3.5,
                                          "target_offset"          => $payload["target_offset"] ?? 0.0,
                                          "volume"                 => $payload["volume"] ?? 5.0,
                                          "support_dilution"                 => $payload["support_dilution"] ?? true,
                                          "region"                 => $payload["region"] ?? "us",
                                          "elements"               => $payload["elements"] ?? [],
                                          "element_units"          => $payload["element_units"] ?? [],
                                          "additive_concentration" => $payload["additive_concentration"] ?? [],
                                          "additive_units"         => $payload["additive_units"] ?? [],
                                          "additive_elements"      => $payload["additive_elements"] ?? [],
                                          "fertilizer_elements"    => $payload["fertilizer_elements"] ?? [],
                                          "target_weeks"           => $payload["target_weeks"] ?? [],
                                          "target_calcium"         => $payload["target_calcium"] ?? [],
                                          "target_magnesium"       => $payload["target_magnesium"] ?? [],
                                      ]);
            return;
        }

        // Default to the index page
        $this->controller->index();
    }

}