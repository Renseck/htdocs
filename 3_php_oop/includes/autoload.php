<?php
 spl_autoload_register(function ($class_name) {
    $path = str_replace('\\', '/', $class_name);
    $file = __DIR__ . "/../classes/$path.php";
    if (file_exists($file)) {
        require_once $file;
    }
 });