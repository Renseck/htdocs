<?php 

require_once __DIR__ . '/TestConfig.php';

// Determine which tests to run
$runModelTests = isset($argv[1]) && $argv[1] === 'models' || !isset($argv[1]);

$specificTest = isset($argv[2]) ? $argv[2] : null;

echo "=================================\n";
echo "Running PHP OOP Project Tests\n";
echo "=================================\n";

$modelTestsDir = __DIR__ . DIRECTORY_SEPARATOR . 'modeltests';

// Run model tests
if ($runModelTests) {
    echo "\n=== Model Tests ===\n";
    
    $modelTests = [
        'UserModelTest.php',
        'ProductModelTest.php',
        'OrderModelTest.php'
    ];
    
    foreach ($modelTests as $test) {
        if ($specificTest === null || $specificTest === $test) {
            $testPath = $modelTestsDir . DIRECTORY_SEPARATOR . $test;
            
            // Check if the file exists
            if (file_exists($testPath)) {
                echo "Running test: $test\n";
                include_once $testPath;
            } else {
                echo "Warning: Test file not found: $testPath\n";
            }
            echo "\n";
        }
    }
}

echo "\n=================================\n";
echo "Tests Complete\n";
echo "=================================\n";