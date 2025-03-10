<?php
require_once "includes/autoload.php";

use controller\mainController;

$controller = new mainController();
$controller->handleRequest();
