<?php 

require_once __DIR__ . '/TestConfig.php';

// Determine which tests to run
$runModelTests = isset($argv[1]) && $argv[1] === 'models' || !isset($argv[1]);
$runPageTests = isset($argv[1]) && $argv[1] === 'pages' || !isset($argv[1]);
$specificTest = isset($argv[2]) ? $argv[2] : null;

echo "=================================\n";
echo "Running PHP OOP Project Tests\n";
echo "=================================\n";

$modelTestsDir = __DIR__ . DIRECTORY_SEPARATOR . 'modeltests';
$pageTestsDir = __DIR__ . DIRECTORY_SEPARATOR . 'pagetests';

// ======================================= Run model tests =========================================
if ($runModelTests) {
    echo "\n=== Model Tests ===\n";
    
    // I've tried using an automatic scandir approach here, but that seems to generate a million 
    // warnings that prohibit nothing but are still annoying to look at so i'll leave i like this
    $modelTests = [
        'usermodeltest.php',
        'productmodeltest.php',
        'ordermodeltest.php'
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

// ======================================= Run page tests ==========================================
if ($runPageTests) {
    echo "\n=== Page Tests ===\n";
    
    // I've tried using an automatic scandir approach here, but that seems to generate a million 
    // warnings that prohibit nothing but are still annoying to look at so i'll leave i like this
    $pageTests = [
        'homepagetest.php',
        'loginpagetest.php',
        'productpagetest.php',
        'webshoppagetest.php',
        'aboutpagetest.php',
        'cartpagetest.php',
        'confirmationpagetest.php',
        'contactpagetest.php',
        'logoutpagetest.php',
        'registerpagetest.php',
    ];
    
    foreach ($pageTests as $test) {
        if ($specificTest === null || $specificTest === $test) {
            $testPath = $pageTestsDir . DIRECTORY_SEPARATOR . $test;
            
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