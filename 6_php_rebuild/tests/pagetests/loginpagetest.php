<?php

require_once __DIR__ . '/../testConfig.php';

class loginPageTest
{
    private $testResult;

    // =============================================================================================
    public function __construct()
    {
        $this->testResult = new testResult();
    }

    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning loginPage tests...\n";

        $this->testPageRendering();

        $this->testResult->printResults(get_class($this));
    }

    // =============================================================================================
    private function testPageRendering()
    {
        // Start buffering to capture page output so we can actually test it
        ob_start();

        try
        {
            $pages = \config\pageConfig::getPages();
            $loginPage = new \view\loginPage($pages);
            $loginPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, '<title>Login</title>') !== false, "Page should have the correct title", $this->testResult);
            assertTrue(strpos($output, 'Login') !== false, "Page should contain login header", $this->testResult);

            // Check form elements
            assertTrue(strpos($output, '<form method="POST"') !== false, "Page should contain a form", $this->testResult);
            assertTrue(strpos($output, 'action=index.php') !== false, "Form should have correct action", $this->testResult);
            assertTrue(strpos($output, 'name="email"') !== false, "Form should have an email field", $this->testResult);
            assertTrue(strpos($output, 'name="password"') !== false, "Form should have a password field", $this->testResult);
            assertTrue(strpos($output, 'type="submit"') !== false, "Form should have a submit button", $this->testResult);

            // Check registration link
            assertTrue(strpos($output, "Register here") !== false, "Page should have a register link", $this->testResult);

        }
        catch (Exception $e)
        {
            $this->testResult->addFailure("Exception rendering page: ". $e->getMessage());
        }
        finally
        {
            // Clean the output buffer
            ob_end_clean();
        }

    }

}

$test = new loginPageTest();
$test->runTests();