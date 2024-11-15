<?php
/*
* File: index.php
* Category: -
* Author: M.Goldenbaum
* Created: 08.11.24 19:28
* Updated: -
*
* Description:
*  -
*/

use Webklex\CalMag\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->route();
