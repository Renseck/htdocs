<?php

require_once "vendor/autoload.php";

use App\controllers\mainController;

// Create main controller and let it handle the request
$controller = new mainController();
$controller->handleRequest();

