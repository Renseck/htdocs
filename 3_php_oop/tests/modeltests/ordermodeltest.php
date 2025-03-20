<?php

use model\productModel;

require_once __DIR__ . '/../testconfig.php';

class orderModelTest
{
    private $orderModel;
    private $productModel;
    private $userModel;
    private $testResult;
    private $testUserId;
    private $testOrderId;
    private $testProducts = [];

    // =============================================================================================
    public function __construct()
    {
        $this->orderModel = new \model\orderModel();
        $this->productModel = new \model\productModel();
        $this->userModel = new \model\userModel();
        $this->testResult = new testResult();

        $this->setupTestData();
    }

    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning orderModel tests...\n";

        if ($this->testUserId && !empty($this->testProducts))
        {
            $this->testCreateOrder();
            $this->testGetOrderById();
            $this->testGetUserOrders();
            $this->testCleanup();
        }
        else
        {
            $this->testResult->addFailure("Test setup failed - couldn't find valid test data");
        }
        

        $this->testResult->printResults(get_class($this));
    }

    // =============================================================================================
    private function setupTestData()
    {
        // Get existing user or create one
        $users = $this->userModel->getUserByEmail("test_user@example.com");
        if ($users)
        {
            $this->testUserId = $users["id"];
        }
        else
        {
            $this->testUserId = $this->userModel->createUser("Test User", "test_user@example.com", "password123");
        }

        // Get some products for testing
        $products = $this->productModel->getAllProducts();
        if (count($products) >= 2)
        {
            // Two should be plenty
            $this->testProducts = array_slice($products, 0, 2);
        }
    }

    // =============================================================================================
    private function testCreateOrder()
    {
        if (!$this->testUserId || empty($this->testProducts))
        {
            $this->testResult->addFailure("Cannot test createOrder without test user and products");
            return;
        }

        // Create cart items for testing
        $cartItems = [];
        foreach ($this->testProducts as $product)
        {
            $cartItems[] = [
                "product_id" => $product["product_id"],
                "quantity" => rand(1, 3)
            ];
        }

        // Create the order
        $result = $this->orderModel->createOrder($this->testUserId, $cartItems);

        assertTrue($result["success"], "Order creation should succeed", $this->testResult);
        assertTrue(isset($result["order_id"]), "Order result should contain a valid order_id", $this->testResult);

        // Save the order ID for subsequent tests
        if ($result["success"] && isset($result["order_id"]))
        {
            $this->testOrderId = $result["order_id"];
        }
    }

    // =============================================================================================
    private function testGetOrderById()
    {
        if (!$this->testOrderId)
        {
            $this->testResult->addFailure("Cannot test getOrderId without a test order");
            return;
        }

        $orderDetails = $this->orderModel->getOrderById($this->testOrderId);

        assertNotNull($orderDetails, "Order details should not be null", $this->testResult);
        assertTrue(isset($orderDetails["order"]), "Order details should contain order info", $this->testResult);
        assertTrue(isset($orderDetails["items"]), "Order details should contain items", $this->testResult);
        assertTrue(isset($orderDetails["total_amount"]), "Order details should contain total amount", $this->testResult);

        $testUserId = (int)$this->testUserId;
        $orderUserId = (int)$orderDetails["order"]["user_id"];

        // Check items count matches what we ordered
        assertEquals(count($this->testProducts), count($orderDetails["items"]),
                     "Order should have correct no. of items", $this->testResult);

        // Check the test order belongs to the test user
        assertEquals($this->testUserId, $orderDetails["order"]["user_id"],
                    "Order should belong to test user", $this->testResult);

        // Test invalid order ID to have a laugh
        $invalidOrder = $this->orderModel->getOrderById(9123285);
        assertEquals(false, $invalidOrder, "Invalid order ID should return false", $this->testResult);
    }

    // =============================================================================================
    private function testGetUserOrders()
    {
        if (!$this->testOrderId)
        {
            $this->testResult->addFailure("Cannot test getUserOrders without a test order");
            return;
        }

        $userOrders = $this->orderModel->getUserOrders($this->testUserId);

        assertTrue(is_array($userOrders), "User orders should be an array", $this->testResult);
        assertTrue(count($userOrders) > 0, "User should have at least one order", $this->testResult);

        // Check first order for correct structure
        if (count($userOrders) > 0)
        {
            $firstOrder = $userOrders[0];
            $testUserId = (int)$this->testUserId;
            $orderUserId = (int)$firstOrder["user_id"];

            assertTrue(isset($firstOrder["order_id"]), "Order should have an ID", $this->testResult);
            assertTrue(isset($firstOrder["user_id"]), "Order should have a user ID", $this->testResult);
            assertTrue(isset($firstOrder["total_amount"]), "Order should have a total amount", $this->testResult);
            assertEquals($this->testUserId, $firstOrder["user_id"], "Order should belong to the test user", $this->testResult);
        }

        // Test with non-existent user ID
        $invalidUserOrders = $this->orderModel->getUserOrders(9123285);
        assertTrue(is_array($invalidUserOrders), "Invalid user should be an array", $this->testResult);
        assertEquals(0, count($invalidUserOrders), "Invalid user should have no orders", $this->testResult);
    }

    // =============================================================================================
    private function testCleanup()
    {
        // We only have to delete the user to get rid of all the things lauched into the DB here
        // due to the lovely CASCADE capability. We'll log what happens to be safe anyway

        if ($this->testOrderId)
        {
            echo "\nTest data includes Order ID: {$this->testOrderId}";
        }

        if ($this->testUserId) {
            $db = \database\databaseConnection::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$this->testUserId]);
            echo "\nCleaned up test user with ID: " . $this->testUserId;
        }
    }
}

$test = new OrderModelTest();
$test->runTests();