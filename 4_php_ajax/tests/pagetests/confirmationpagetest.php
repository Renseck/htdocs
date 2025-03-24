<?php
// filepath: c:\xampp\htdocs\3_php_oop\tests\pagetests\confirmationpagetest.php

require_once __DIR__ . '/../testConfig.php';

class confirmationPageTest
{
    private $testResult;
    private $orderModel;
    private $userModel;
    private $testUserId;
    private $testOrderId;
    
    // =============================================================================================
    public function __construct()
    {
        $this->testResult = new testResult();
        $this->orderModel = new \model\orderModel();
        $this->userModel = new \model\userModel();
        
        // Setup test user and mock order for testing
        $this->setupTestData();
    }
    
    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning confirmationPage tests...\n";
        
        $this->testPageRenderingNotLoggedIn();
        $this->testPageRenderingLoggedInNoOrder();
        
        // Only run this test if we have successfully created a test order
        if ($this->testOrderId) {
            $this->testPageRenderingWithOrder();
        }
        
        $this->testCleanup();
        $this->testResult->printResults(get_class($this));
    }
    
    // =============================================================================================
    private function setupTestData()
    {
        // Get or create a test user
        $testUser = $this->userModel->getUserByEmail("confirmation_test@example.com");
        if ($testUser) {
            $this->testUserId = $testUser["id"];
        } else {
            $this->testUserId = $this->userModel->createUser("Confirmation Test", "confirmation_test@example.com", "password123");
        }
    }
    
    // =============================================================================================
    private function testPageRenderingNotLoggedIn()
    {
        // Ensure we're logged out
        if (\controller\sessionController::isLoggedIn()) {
            \controller\sessionController::logout();
        }
        
        // Start output buffering
        ob_start();
        
        try {
            $pages = \config\pageConfig::getPages();
            $confirmationPage = new \view\confirmationPage($pages);
            $confirmationPage->show();
            
            $output = ob_get_contents();
            
            // Check basic page structure
            assertTrue(strpos($output, '<title>Order confirmation</title>') !== false, 
                       "Page should have correct title", $this->testResult);
            
            assertTrue(strpos($output, 'Order confirmation') !== false, 
                       "Page should contain confirmation heading", $this->testResult);
            
            // Should show login prompt
            assertTrue(strpos($output, 'log in') !== false, 
                       "Page should prompt users to log in", $this->testResult);
            
        } catch (Exception $e) {
            $this->testResult->addFailure("Exception rendering page: " . $e->getMessage());
        } finally {
            // Clean up
            ob_end_clean();
        }
    }
    
    // =============================================================================================
    private function testPageRenderingLoggedInNoOrder()
    {
        // Simulate being logged in
        $_SESSION['user'] = [
            'id' => $this->testUserId,
            'name' => 'Confirmation Test',
            'email' => 'confirmation_test@example.com'
        ];
        
        // Clear any last order ID
        unset($_SESSION["last_order_id"]);
        
        // Start output buffering
        ob_start();
        
        try {
            $pages = \config\pageConfig::getPages();
            $confirmationPage = new \view\confirmationPage($pages);
            $confirmationPage->show();
            
            $output = ob_get_contents();
            
            // Check basic page structure
            assertTrue(strpos($output, '<title>Order confirmation</title>') !== false, 
                       "Page should have correct title", $this->testResult);
            
            // Should show no order details message
            assertTrue(strpos($output, 'No order details available') !== false, 
                       "Page should show no order details message when none exist", $this->testResult);
            
            assertTrue(strpos($output, 'Continue Shopping') !== false, 
                       "Page should have continue shopping link", $this->testResult);
            
        } catch (Exception $e) {
            $this->testResult->addFailure("Exception rendering page: " . $e->getMessage());
        } finally {
            // Clean up
            ob_end_clean();
        }
    }
    
    // =============================================================================================
    private function testPageRenderingWithOrder()
    {
        // Create a real test order to ensure we have valid order data
        $productModel = new \model\productModel();
        $products = $productModel->getAllProducts();
        
        if (count($products) > 0) {
            // Create cart items with 2 products (or fewer if not enough products)
            $cartItems = [];
            $numProducts = min(count($products), 2);
            
            for ($i = 0; $i < $numProducts; $i++) {
                $cartItems[] = [
                    "product_id" => $products[$i]["product_id"],
                    "quantity" => 1
                ];
            }
            
            // Create the order
            $result = $this->orderModel->createOrder($this->testUserId, $cartItems);
            
            if ($result["success"]) {
                $this->testOrderId = $result["order_id"];
                
                // Set the order ID in the session
                $_SESSION["last_order_id"] = $this->testOrderId;
                
                // Ensure we're logged in
                $_SESSION['user'] = [
                    'id' => $this->testUserId,
                    'name' => 'Confirmation Test',
                    'email' => 'confirmation_test@example.com'
                ];
                
                // Start output buffering
                ob_start();
                
                try {
                    $pages = \config\pageConfig::getPages();
                    $confirmationPage = new \view\confirmationPage($pages);
                    $confirmationPage->show();
                    
                    $output = ob_get_contents();
                    
                    // Check basic page structure and order confirmation elements
                    assertTrue(strpos($output, '<title>Order confirmation</title>') !== false, 
                               "Page should have correct title", $this->testResult);
                    
                    assertTrue(strpos($output, 'Thank you for your order') !== false, 
                               "Page should contain thank you message", $this->testResult);
                    
                    assertTrue(strpos($output, 'Order #' . $this->testOrderId) !== false, 
                               "Page should show the order number", $this->testResult);
                    
                    assertTrue(strpos($output, '<div class="order-summary">') !== false, 
                               "Page should have order summary section", $this->testResult);
                    
                    assertTrue(strpos($output, '<table class="order-items">') !== false, 
                               "Page should have order items table", $this->testResult);
                    
                    // Check for product information in the output
                    $productName = htmlspecialchars($products[0]["name"]);
                    assertTrue(strpos($output, $productName) !== false, 
                               "Page should display product name", $this->testResult);
                    
                    // Check for total
                    assertTrue(strpos($output, '<strong>Total:</strong>') !== false, 
                               "Page should display order total", $this->testResult);
                    
                    // Check for continue shopping link
                    assertTrue(strpos($output, 'Continue Shopping') !== false, 
                               "Page should have continue shopping link", $this->testResult);
                    
                } catch (Exception $e) {
                    $this->testResult->addFailure("Exception rendering page: " . $e->getMessage());
                } finally {
                    // Clean up
                    ob_end_clean();
                    
                    // Clear the session order ID
                    unset($_SESSION["last_order_id"]);
                }
            } else {
                $this->testResult->addFailure("Failed to create test order: " . $result["message"]);
            }
        } else {
            $this->testResult->addFailure("No products found for testing order confirmation");
        }
    }
    
    // =============================================================================================
    private function testCleanup()
    {
        // Clean up test data
        if ($this->testUserId) {
            // Due to CASCADE, deleting the user will delete all associated orders
            $db = \database\databaseConnection::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$this->testUserId]);
            
            echo "\nCleaned up test user ID: " . $this->testUserId;
            
            if ($this->testOrderId) {
                echo " (including order ID: " . $this->testOrderId . ")";
            }
        }
        
        // Clean up session
        unset($_SESSION['user']);
        unset($_SESSION["last_order_id"]);
    }
}

// Run the test
$test = new confirmationPageTest();
$test->runTests();