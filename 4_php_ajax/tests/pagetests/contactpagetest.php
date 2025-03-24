<?php

require_once __DIR__ . '/../testConfig.php';

class contactPageTest
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
        echo "\nRunning contactPage tests...\n";

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
            $contactPage = new \view\contactPage($pages);
            $contactPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, '<title>Contact</title>') !== false, "Page should have the correct title", $this->testResult);
            assertTrue(strpos($output, 'Contact') !== false, "Page should contain contact heading", $this->testResult);

            // Check form elements
            assertTrue(strpos($output, '<form method="POST"') !== false, "Page should contain a form", $this->testResult);
            assertTrue(strpos($output, 'action=index.php?page=contact&action=contact') !== false, "Form should have correct action", $this->testResult);
            assertTrue(strpos($output, 'name="name"') !== false, "Form should have a name field", $this->testResult);
            assertTrue(strpos($output, 'name="email"') !== false, "Form should have an email field", $this->testResult);
            assertTrue(strpos($output, 'name="message"') !== false, "Form should have a message field", $this->testResult);
            assertTrue(strpos($output, 'type="submit"') !== false, "Form should have a submit button", $this->testResult);

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

$test = new contactPageTest();
$test->runTests();