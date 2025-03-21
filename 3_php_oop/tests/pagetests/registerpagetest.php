<?php

require_once __DIR__ . '/../testConfig.php';

class registerPageTest
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
        echo "\nRunning registerPage tests...\n";

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
            $registerPage = new \view\registerPage($pages);
            $registerPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, '<title>Register</title>') !== false, "Page should have the correct title", $this->testResult);
            assertTrue(strpos($output, 'Register') !== false, "Page should contain login heading", $this->testResult);

            // Check form elements
            assertTrue(strpos($output, '<form method="POST"') !== false, "Page should contain a form", $this->testResult);
            assertTrue(strpos($output, 'action=index.php?page=register&action=register') !== false, "Form should have correct action", $this->testResult);
            assertTrue(strpos($output, 'name="name"') !== false, "Form should have a name field", $this->testResult);
            assertTrue(strpos($output, 'name="email"') !== false, "Form should have an email field", $this->testResult);
            assertTrue(strpos($output, 'name="password"') !== false, "Form should have a password field", $this->testResult);
            assertTrue(strpos($output, 'name="password_repeat"') !== false, "Form should have a password confirmation field", $this->testResult);
            assertTrue(strpos($output, 'type="submit"') !== false, "Form should have a submit button", $this->testResult);

            // Check for login link
            assertTrue(strpos($output, "Already have an account") !== false, "Page should ask if user already has an account", $this->testResult);
            assertTrue(strpos($output, "Login here") !== false, "Page should have a login link", $this->testResult);

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

$test = new registerPageTest();
$test->runTests();