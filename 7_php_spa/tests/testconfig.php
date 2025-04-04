<?php 

require_once __DIR__ . '/../includes/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class testSessionHandler
{
    private static $initialized = false;

    // =============================================================================================
    public static function init()
    {
        if (!self::$initialized)
        {
            if (session_status() === PHP_SESSION_NONE)
            {
                session_start();
            }
            self::$initialized = true;
        }
    }

    // =============================================================================================
    public static function reset()
    {
        if (session_status() === PHP_SESSION_ACTIVE)
        {
            session_unset();
            session_destroy();
        }
        self::$initialized = false;
    }
}

class testResult 
{
    public $passed = 0;
    public $failed = 0;
    public $errors = [];
    
    // =============================================================================================
    public function addSuccess()
    {
        $this->passed++;
    }

    // =============================================================================================
    public function addFailure($message)
    {
        $this->failed++;
        $this->errors[] = $message;
    }

    // =============================================================================================
    public function printResults($testName)
    {
        echo "\n=== Results for {$testName} ===\n";
        echo "Passed: {$this->passed}\n";
        echo "Failed: {$this->failed}\n";

        if (!empty($this->errors))
        {
            echo "\nErrors:\n";
            foreach ($this->errors as $error)
            {
                echo "- {$error}\n";
            }
        }

        if ($this->failed === 0) {
            echo "\n✅ All tests passed!\n";
        } else {
            echo "\n❌ Some tests failed!\n";
        }
    }

    // =============================================================================================
}

// =================================================================================================

/**
 * We're passing testResult as a reference so the instance of it remains alive throughout testing
 */
function assertEquals($expected, $actual, $message = "", &$testResult)
{
    // Let's do a looser comparison for numerics
    if (is_numeric($expected) && is_numeric($actual))
    {
        if($expected == $actual)
        {
            $testResult->addSuccess();
            echo ".";
            return;
        }
    }
    elseif ($expected === $actual)
    {
        $testResult->addSuccess();
        echo "."; // Visual indicator of success
        return;
    }
    
    $errorMsg = $message ? $message : "Expected " . var_export($expected, true) . " but got " . var_export($actual, true);
    $testResult->addFailure($errorMsg);
    echo "F"; // Visual indicator of failure
    
}

// =================================================================================================
function assertTrue($condition, $message = "", &$testResult)
{
    if ($condition)
    {
        $testResult->addSuccess();
        echo ".";
    }
    else
    {
        $errorMsg = $message ? $message : "Expected true but got false";
        $testResult->addFailure($errorMsg);
        echo "F";
    }
}

// =================================================================================================
function assertFalse($condition, $message = "", &$testResult)
{
    if (!$condition)
    {
        $testResult->addSuccess();
        echo ".";
    }
    else
    {
        $errorMsg = $message ? $message : "Expected false but got true";
        $testResult->addFailure($errorMsg);
        echo "F";
    }
}

// =================================================================================================
function assertNotNull($value, $message = '', &$testResult) {
    if ($value !== null) {
        $testResult->addSuccess();
        echo ".";
    } 
    else 
    {
        $errorMsg = $message ? $message : "Expected non-null value but got null";
        $testResult->addFailure($errorMsg);
        echo "F";
    }
}