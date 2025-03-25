<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

echo "Database user : " . $_ENV["db_user"] . "\n";
echo "Database pw : " . $_ENV["db_pass"] . "\n";