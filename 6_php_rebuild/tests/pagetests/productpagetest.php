<?php

require_once __DIR__ . '/../testConfig.php';

class productPageTest
{
    private $testResult;
    private $productModel;
    private $testProductId;

    // =============================================================================================
    public function __construct()
    {
        $this->testResult = new testResult();
        $this->productModel = new \model\productModel();

        // Get a valid product ID for testing
        $products = $this->productModel->getAllProducts();
        if (!empty($products))
        {
            $this->testProductId = $products[0]["product_id"];
        }
    }

    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning productPage tests...\n";

        if ($this->testProductId)
        {
            $this->testValidProductPage();
            $this->testInvalidProductPage();
        }
        else
        {
            $this->testResult->addFailure("No product found for testing");
        }

        $this->testResult->printResults(get_class($this));
    }

    // =============================================================================================
    private function testValidProductPage()
    {
        // Simulate GET parameter
        $_GET["id"] = $this->testProductId;

        // Start buffering to capture page output so we can actually test it
        ob_start();

        try
        {
            $product = $this->productModel->getProductById($this->testProductId);

            $pages = \config\pageConfig::getPages();
            $productPage = new \view\productPage($pages);
            $productPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, htmlspecialchars($product["name"])) !== false,
                        "Page should contain product name", $this->testResult);

            assertTrue(strpos($output, htmlspecialchars($product["description"])) !== false,
                        "Page should contain product description", $this->testResult);

            assertTrue(strpos($output, htmlspecialchars($product["price"])) !== false,
                        "Page should contain product price", $this->testResult);

            assertTrue(strpos($output, '<div class="product_container">') !== false,
                        "Page should have product container", $this->testResult);

            assertTrue(strpos($output, '<div class="img_highlight">') !== false,
                        "Page should have image section", $this->testResult);

            assertTrue(strpos($output, '<div class="order_card">') !== false,
                        "Page should contain order card", $this->testResult);


            // If user is not logged in, show a login button
            if (!\controller\sessionController::isLoggedIn())
            {
                assertTrue(strpos($output, 'Login to order') !== false,
                "Page should show login prompt when not logged in", $this->testResult);
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

            // Clean up GET params
            unset($_GET["id"]);
        }

    }

    // =============================================================================================
    private function testInvalidProductPage()
    {
        // Simulate GET parameter
        $_GET["id"] = 99999;

        // Start buffering to capture page output so we can actually test it
        ob_start();

        try 
        {
            $pages = \config\pageConfig::getPages();
            $productPage = new \view\productPage($pages);
            $productPage->show();

            $output = ob_get_contents();

            // Check if not found message is displayed
            assertTrue(strpos($output, 'product-not-found') !== false, 
                      "Page should show not found message for invalid product", $this->testResult);
            
            assertTrue(strpos($output, 'Return to Shop') !== false, 
                      "Page should have link back to shop", $this->testResult);

        }
        catch (Exception $e)
        {
            $this->testResult->addFailure("Exception rendering page: ". $e->getMessage());
        }
        finally
        {
            // Clean the output buffer
            ob_end_clean();

            // Clean up GET params
            unset($_GET["id"]);
        }

    }

}

$test = new productPageTest();
$test->runTests();