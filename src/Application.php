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
    const VERSION = "1.5.0";

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
        // Check if the current request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check for potential json request and call the api if this is the case
            if ($_SERVER["HTTP_ACCEPT"] === "application/json" || $_SERVER["CONTENT_TYPE"] === "application/json") {
                $payload = json_decode(file_get_contents('php://input'), true);
                $this->controller->api($payload);
                return;

            }
            $this->controller->result($_POST);
            return;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $payload = $_GET["p"] ?? "";

            // Check if a shared payload is present
            if ($payload !== "") {
                $payload = base64_decode($payload);
                $payload = json_decode($payload, true);
                if (is_array($payload)) {
                    $this->controller->result([
                                                  "fertilizer"    => $_payload["fertilizer"] ?? "",
                                                  "additive"      => $_payload["additive"] ?? [],
                                                  "ratio"         => $_payload["ratio"] ?? 3.5,
                                                  "volume"        => $_payload["volume"] ?? 5.0,
                                                  "region"        => $_payload["region"] ?? "us",
                                                  "elements"      => $_payload["elements"] ?? [],
                                                  "element_units" => $_payload["element_units"] ?? [],
                                                  "additive_concentration" => $_payload["additive_concentration"] ?? [],
                                              ]);
                    return;
                }
            }
        }

        // Default to the index page
        $this->controller->index();
    }

}