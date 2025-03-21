<?php

use model\productModel;

require_once __DIR__ . '/../testconfig.php';

class webshopPageTest
{
    private $testResult;
    private $productModel;

    // =============================================================================================
    public function __construct()
    {
        $this->testResult = new testResult();
        $this->productModel = new \model\productModel();
    }

    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning webshopPage tests...\n";

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
            $webshopPage = new \view\webshopPage($pages);
            $webshopPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, '<title>Webshop</title>') !== false, "Page should have the correct title", $this->testResult);
            assertTrue(strpos($output, 'Webshop') !== false, "Page should contain wW0bshop heading", $this->testResult);
            assertTrue(strpos($output, '<div class="webshop">') !== false, "Page should contain product grid", $this->testResult);

            // Get some products to verify their presence
            $products = $this->productModel->getAllProducts();
            if (!empty($products))
            {
                $firstProduct = $products[0];
                $productName = htmlspecialchars($firstProduct["name"]);
                $productPrice = number_format((float)$firstProduct["price"], 2);

                assertTrue(strpos($output, $productName) !== false, "Page should display at least one product name", $this->testResult);
                assertTrue(strpos($output, $productPrice) !== false, "Page should display product price", $this->testResult);
            }

            // Check the "add to cart" button or "login to order" links are present
            if(\controller\sessionController::isLoggedIn())
            {
                assertTrue(strpos($output, 'Add to Cart') !== false, "Logged-in users should see Add to Cart button", $this->testResult);
            }
            else
            {
                assertTrue(strpos($output, 'Login to order') !== false, "Non-logged-in users should see login link", $this->testResult);
            }
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

$test = new webshopPageTest();
$test->runTests();