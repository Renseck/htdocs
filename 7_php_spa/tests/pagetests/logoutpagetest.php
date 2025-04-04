<?php

require_once __DIR__ . '/../testconfig.php';

class logoutPageTest
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
        echo "\nRunning logoutPage tests...\n";

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
            $logoutPage = new \view\logoutPage($pages);
            $logoutPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, '<title>Logout</title>') !== false, "Page should have the correct title", $this->testResult);
            assertTrue(strpos($output, 'You have been logged out') !== false, "Page should contain a logout confirmation message", $this->testResult);

            // Check the navigation links
            assertTrue(strpos($output, '<ul class="menu">') !== false, "Page should contain navigation menu", $this->testResult);
            assertTrue(strpos($output, 'HOME') !== false, "Page should contain a HOME link", $this->testResult);
            assertTrue(strpos($output, 'ABOUT') !== false, "Page should contain a ABOUT link", $this->testResult);

            // Check the footer
            assertTrue(strpos($output, date("Y")) !== false, "Page should contain current year in footer", $this->testResult);
            assertTrue(strpos($output, 'Rens van Eck') !== false, "Page should contain author name in footer", $this->testResult);
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

$test = new logoutPageTest();
$test->runTests();