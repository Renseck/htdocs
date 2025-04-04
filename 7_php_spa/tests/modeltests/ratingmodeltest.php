<?php

use model\ratingModel;
use model\productModel;
use model\userModel;

require_once __DIR__ . "/../testconfig.php";

class ratingModelTest
{
    private $ratingModel;
    private $productModel;
    private $userModel;
    private $testResult;
    private $testUserId;
    private $testProductId;
    private $testRating = 4;

    // =============================================================================================
    public function __construct()
    {
        $this->ratingModel = new ratingModel();
        $this->productModel = new productModel();
        $this->userModel = new userModel();
        $this->testResult = new testResult();   

        $this->setupTestData();
    }

    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning ratingModel tests...\n";

        if ($this->testUserId && !empty($this->testProductId))
        {
            $this->testRateProduct();
            $this->testGetUserRating();
            $this->testGetAverageRating();
            $this->testGetProductRatings();
            $this->testGetUserRatings();
            $this->testDeleteRating();
            
        }
        else
        {
            $this->testResult->addFailure("Test setup failed - couldn't find valid test data");
        }
        

        $this->testResult->printResults(get_class($this));
        $this->testCleanup();
    }

    // =============================================================================================
    private function setupTestData()
    {
        // Get a user ID
        $user = $this->userModel->getUserByEmail("test_user@example.com");
        if ($user)

        {
            $this->testUserId = $user["id"];
        }
        else
        {
            $this->testUserId = $this->userModel->createUser(
                "Test User",
                "test_user@example.com",
                "password123"
            );
        }

        // Get a product
        $products = $this->productModel->getAllProducts();
        if (count($products) > 0)
        {
            $this->testProductId = $products[0]["product_id"];
        }

        // Get rid of any existing ratings
        if ($this->testUserId && $this->testProductId)
        {
            $this->ratingModel->deleteRating($this->testProductId, $this->testUserId);
        }
    }

    // =============================================================================================
    private function testRateProduct()
    {
        // Test a valid rating
        $result = $this->ratingModel->rateProduct($this->testProductId, $this->testUserId, $this->testRating);
        assertTrue($result !== false, "Valid rating should succeed", $this->testResult);

        // Test a duplicate rating
        $duplicateResult = $this->ratingModel->rateProduct($this->testProductId, $this->testUserId, $this->testRating);
        assertEquals(false, $duplicateResult, "Duplicate rating should fail", $this->testResult);

        // Test invalid rating value
        $invalidLowResult = $this->ratingModel->rateProduct($this->testProductId, $this->testUserId, 0);
        assertEquals(false, $invalidLowResult, "Rating below 1 should fail", $this->testResult);

        $invalidHighResult = $this->ratingModel->rateProduct($this->testProductId, $this->testUserId, 6);
        assertEquals(false, $invalidHighResult, "Rating above 5 should fail", $this->testResult);
    }

    // =============================================================================================
    private function testGetUserRating()
    {
        $rating = $this->ratingModel->getUserRating($this->testProductId, $this->testUserId);
        assertEquals($this->testRating, $rating, "Retrieved rating should match what was saved", $this->testResult);

        // Test non-existent rating
        $nonExistentRating = $this->ratingModel->getUserRating($this->testProductId, 923912);
        assertEquals(null, $nonExistentRating, "Non-existent rating should return null", $this->testResult);
    }

    // =============================================================================================
    private function testGetAverageRating()
    {
        $averageRating = $this->ratingModel->getAverageRating($this->testProductId);

        // Test averageRating contents
        assertTrue(is_array($averageRating), "Average rating should return an array", $this->testResult);
        assertTrue(isset($averageRating["average"]), "Average rating should contain 'average' key", $this->testResult);
        assertTrue(isset($averageRating["count"]), "Average rating should contain 'count' key", $this->testResult);

        // Count should be at least 1 since we added a rating
        assertTrue($averageRating["count"] > 0, "Rating count should be at least 1", $this->testResult);

        // Test non-existent product
        $nonExistentAverage = $this->ratingModel->getAverageRating(90213);
        assertEquals(0, $nonExistentAverage["average"], "Non-existent product should have 0 average", $this->testResult);
        assertEquals(0, $nonExistentAverage["count"], "Non-existent product should have 0 count", $this->testResult);

    }

    // =============================================================================================
    private function testGetProductRatings()
    {
        $productRatings = $this->ratingModel->getProductRatings($this->testProductId);

        assertTrue(is_array($productRatings), "Product ratings should be an array", $this->testResult);
        assertTrue(count($productRatings) > 0, "Product should have at least one rating", $this->testResult);

        // Test the first rating we find
        if (count($productRatings) > 0)
        {
            $firstRating = $productRatings[0];
            assertTrue(isset($firstRating["product_id"]), "Rating should contain product_id", $this->testResult);
            assertTrue(isset($firstRating["user_id"]), "Rating should contain user_id", $this->testResult);
            assertTrue(isset($firstRating["rating"]), "Rating should contain rating", $this->testResult);
        }

        // Test on non-existent product
        $nonExistentRatings = $this->ratingModel->getProductRatings(192328);
        assertTrue(is_array($nonExistentRatings), "Non-existent product ratings should be an array", $this->testResult);
        assertEquals(0, count($nonExistentRatings), "Non-existent product should have no ratings", $this->testResult);
    }

    // =============================================================================================
    private function testGetUserRatings()
    {
        $userRatings = $this->ratingModel->getUserRatings($this->testUserId);

        assertTrue(is_array($userRatings), "User ratings should be an array", $this->testResult);
        assertTrue(count($userRatings) > 0, "User should have at least one rating", $this->testResult);

        // Test the first rating we find
        if (count($userRatings) > 0)
        {
            $firstRating = $userRatings[0];
            assertTrue(isset($firstRating["product_id"]), "Rating should contain product_id", $this->testResult);
            assertTrue(isset($firstRating["user_id"]), "Rating should contain user_id", $this->testResult);
            assertTrue(isset($firstRating["rating"]), "Rating should contain rating", $this->testResult);
            assertTrue(isset($firstRating["product_name"]), "Rating should contain product-name", $this->testResult);
        }

        // Test non-existent user
        $nonExistentRatings = $this->ratingModel->getUserRatings(813912);
        assertTrue(is_array($nonExistentRatings), "Non-existent user ratings should be an array", $this->testResult);
        assertEquals(0, count($nonExistentRatings) , "Non-existent user should have no ratings", $this->testResult);
    }   

    // =============================================================================================
    private function testDeleteRating()
    {
        $ratingBefore = $this->ratingModel->getUserRating($this->testProductId, $this->testUserId);
        assertNotNull($ratingBefore, "Rating should exist before deletion", $this->testResult);

        $deleteResult = $this->ratingModel->deleteRating($this->testProductId, $this->testUserId);
        assertTrue($deleteResult !== false, "Rating deletion should succeed", $this->testResult);

        $ratingAfter = $this->ratingModel->getUserRating($this->testProductId, $this->testUserId);
        assertEquals(null, $ratingAfter, "Rating should not exist after deletion", $this->testResult);

        $nonExistentDelete = $this->ratingModel->deleteRating($this->testProductId, 812389);
        assertEquals(false, $nonExistentDelete, "Deleting non-existent rating should return false", $this->testResult);
    }

    // =============================================================================================
    private function testCleanup()
    {
        if ($this->testUserId) {
            // Clean up test rating if it still exists
            echo "\nCleaned up test rating";
            $this->ratingModel->deleteRating($this->testProductId, $this->testUserId);
            
            // If this is a test user we created, delete it
            if (!$this->userModel->getUserByEmail("test_rating_user@example.com")) {
                $db = \database\databaseConnection::getInstance()->getConnection();
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$this->testUserId]);
                echo "\nCleaned up test user with ID: " . $this->testUserId;
            }
        }
    }

    // =============================================================================================
}

$test = new ratingModelTest();
$test->runTests();