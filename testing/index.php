<?php

// Include the autoloader
require_once "_src/autoloader.php";

// Use namespaced class
use App\controllers\multiController;

$multiController = new multiController();
$multiController->handleRequest();