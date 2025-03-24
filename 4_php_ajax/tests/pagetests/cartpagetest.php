<?php

require_once __DIR__ . '/../testConfig.php';

class cartPageTest
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
        echo "\nRunning cartPage tests...\n";


        $this->testPageRenderingNotLoggedIn();
        $this->testPageRenderingLoggedIn();

        $this->testResult->printResults(get_class($this));
    }

    // =============================================================================================
    private function testPageRenderingNotLoggedIn()
    {
        // Ensure we're logged out
        if (\controller\sessionController::isLoggedIn()) {
            \controller\sessionController::logout();
        }

        // Start buffering to capture page output so we can actually test it
        ob_start();

        try
        {
            $pages = \config\pageConfig::getPages();
            $cartPage = new \view\cartPage($pages);
            $cartPage->show();

            $output = ob_get_contents();

            // Check if the key elements are present
            assertTrue(strpos($output, '<title>Shopping cart</title>') !== false, 
                      "Page should have correct title", $this->testResult);
            
            // Test for login message when not logged in
            assertTrue(strpos($output, 'log in') !== false, 
                      "Page should prompt users to log in", $this->testResult);

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

    // =============================================================================================
    private function testPageRenderingLoggedIn()
    {
        // Simulate being logged in - This way, we don't have to mess around with DB stuff
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Cart Test',
            'email' => 'cart_test@example.com'
        ];

        // Start buffering to capture page output so we can actually test it
        ob_start();

        try 
        {
            $pages = \config\pageConfig::getPages();
            $cartPage = new \view\cartPage($pages);
            $cartPage->show();

            $output = ob_get_contents();

            // Test basic page structure
            assertTrue(strpos($output, '<title>Shopping cart</title>') !== false, 
                      "Page should have correct title", $this->testResult);
            
            // Test for empty cart message or cart table
            $containsEmptyMessage = strpos($output, 'Your cart is empty') !== false;
            $containsCartTable = strpos($output, 'shopping_cart') !== false;
            
            assertTrue($containsEmptyMessage || $containsCartTable, 
                      "Page should either show empty cart message or cart table", $this->testResult);
            
            // For empty cart, check for "Continue Shopping" link
            if ($containsEmptyMessage) {
                assertTrue(strpos($output, 'Continue Shopping') !== false, 
                          "Empty cart should show Continue Shopping link", $this->testResult);
            }
            
            // If cart has items, check for cart table headers
            if ($containsCartTable) {
                assertTrue(strpos($output, '<th>Product</th>') !== false, 
                          "Cart table should have Product header", $this->testResult);
                assertTrue(strpos($output, '<th>Quantity</th>') !== false, 
                          "Cart table should have Quantity header", $this->testResult);
                assertTrue(strpos($output, '<th>Price</th>') !== false, 
                          "Cart table should have Price header", $this->testResult);
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
            unset($_SESSION["user"]);
        }

    }

}

$test = new cartPageTest();
$test->runTests();