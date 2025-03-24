<?php

require_once __DIR__ . '/../testconfig.php';

class aboutPageTest
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
        echo "\nRunning aboutPage tests...\n";

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
            $aboutPage = new \view\aboutPage($pages);
            $aboutPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, '<title>About</title>') !== false, "Page should have the correct title", $this->testResult);
            assertTrue(strpos($output, 'About') !== false, "Page should contain expected heading", $this->testResult);

            // Check page content
            assertTrue(strpos($output, 'about me') !== false || strpos($output, "About me") !== false,
                             "Page should contain about me content", $this->testResult);

            assertTrue(strpos($output, 'Rens') !== false || strpos($output, "About me") !== false,
                             "Page should contain name", $this->testResult);

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

$test = new aboutPageTest();
$test->runTests();