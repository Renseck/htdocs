<?php

use model\productModel;

require_once __DIR__ . '/../testconfig.php';

class productModelTest
{
    private $productModel;
    private $testResult;

    // =============================================================================================
    public function __construct()
    {
        $this->productModel = new \model\productModel();
        $this->testResult = new testResult();
    }

    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning productModel tests...\n";

        $this->testGetAllProducts();
        $this->testGetProductById();
        $this->testSearchProducts();

        $this->testResult->printResults(get_class($this));
    }

    // =============================================================================================
    private function testGetAllProducts()
    {
        $products = $this->productModel->getAllProducts();
        assertTrue(is_array($products), "getAllProducts should return an array", $this->testResult);
        assertTrue(count($products) > 0, "Should have at least one product", $this->testResult);

        // Check product structure
        if (count($products) > 0) 
        {
            $product = $products[0];
            assertTrue(isset($product["product_id"]), "Product should have an ID", $this->testResult);
            assertTrue(isset($product["name"]), "Product should have a name", $this->testResult);
            assertTrue(isset($product["price"]), "Product should have a price", $this->testResult);
        }

        // Test the ordering queries
        $productsAsc = $this->productModel->getAllProducts("name", "ASC");
        $productsDesc = $this->productModel->getAllProducts("name", "DESC");

        if (count($productsAsc) > 1 && count($productsDesc) > 1) 
        {
            // Check if the ordering actually works
            assertNotNull($productsAsc[0]["name"], "First product name (ASC) should not be null", $this->testResult);
            assertNotNull($productsDesc[0]["name"], "First product name (DESC) should not be null", $this->testResult);

            // Provided the ordering works, these two names should not be the same
            $firstAsc = $productsAsc[0]["name"];
            $firstDesc = $productsDesc[0]["name"];
            assertTrue($firstAsc !== $firstDesc, "ASC and DESC ordering should give different first products", $this->testResult);
        }
    }

    // =============================================================================================
    private function testGetProductById()
    {
        // Get first product to get a valid ID to test with
        $products = $this->productModel->getAllProducts();
        if(count($products) > 0)
        {
            $firstId = $products[0]["product_id"];
            $product = $this->productModel->getProductById($firstId);

            assertNotNull($product, "Product should be found by ID", $this->testResult);
            assertEquals($firstId, $product["product_id"], "Product ID should match", $this->testResult);
        }

        // Test with invalid ID
        $invalidProduct = $this->productModel->getProductById(99999);
        assertEquals(false, $invalidProduct, "Invalid product ID should return false", $this->testResult);
    }
    
    // =============================================================================================
    private function testSearchProducts()
    {
        // Let's first get a product to search for
        $products = $this->productModel->getAllProducts();
        if(count($products) > 0)
        {
            $firstProduct = $products[0];
            $nameToSearch = substr($firstProduct["name"], 0, 3); 
            
            $searchResults = $this->productModel->searchProducts($nameToSearch);
            assertTrue(is_array($searchResults), "Search results should be an array", $this->testResult);
            assertTrue(count($searchResults) > 0, "Search should find at least one product", $this->testResult);
        }

        // Test with a product that doesn't exist at all
        $randomSearch = "xasdkasoidjao" . time();
        $noResults = $this->productModel->searchProducts($randomSearch);
        assertTrue(is_array($noResults), "No results should still be an array", $this->testResult);
        assertEquals(0, count($noResults), "Random search should return an empty array", $this->testResult);
    }
}

$test = new productModelTest();
$test->runTests();