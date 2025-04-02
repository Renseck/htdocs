<?php

namespace controller;

use model\productModel;
use factories\formatterFactory;

class apiController
{
    private $productModel;
    private $formatterFactory;
    public array $validTypes = ["html", "json", "xml"];

    // =============================================================================================
    public function __construct()
    {
        $this->productModel = new productModel();
        $this->formatterFactory = new formatterFactory();
    }

    // =============================================================================================
    /**
     * Format response in requested type
     * @param array $data Data to be formatted
     * @param string $type Response type
     * @return void
     */
    private function sendResponse(array $data, string $type) : void
    {
        try {
            $formatter = $this->formatterFactory->create($type);
            $this->setContentTypeHeader($formatter->getContentType());
            echo $formatter->formatResponse($data);
        }
        catch (\InvalidArgumentException $e)
        {
            $formatter = $this->formatterFactory->create("default");
            $this->setContentTypeHeader($formatter->getContentType());
            echo $formatter->formatError($e->getMessage());
        }
    }

    // =============================================================================================
    /**
     * Send error message to client
     * @param string $message Message to send
     * @param string $type Response type
     * @return void
     */
    public function sendErrorResponse(string $message, string $type) : void
    {
        try {
            $formatter = $this->formatterFactory->create($type);
            $this->setContentTypeHeader($formatter->getContentType());
            echo $formatter->formatError($message);
        }
        catch (\InvalidArgumentException $e)
        {
            $formatter = $this->formatterFactory->create("default");
            $this->setContentTypeHeader($formatter->getContentType());
            echo "Error: " . $e->getMessage();
        }
    }

    // =============================================================================================
    /**
     * Get all products from product model and respond in type
     * @param string $type Response type
     * @return void
     */
    public function getAllProducts(string $type) : void
    {
        $products = $this->productModel->getAllProducts();
        $this->sendResponse($products, $type);
    }

    // =============================================================================================
    /**
     * Get product by ID and respond in type
     * @param int $id Product ID
     * @param string $typeResponse type
     * @return void
     */
    public function getProductById(int $id, string $type) : void
    {
        if ($id <= 0)
        {
            $this->sendErrorResponse("Invalid product ID", $type);
            return;
        }

        $product = $this->productModel->getProductById($id);

        if (!$product)
        {
            $this->sendErrorResponse("Product not found", $type);
            return;
        }

        if (!is_array($product)) {
            $this->sendErrorResponse("Invalid product data format", $type);
            return;
        }

        $this->sendResponse($product, $type);
    }

    // =============================================================================================
    /**
     * Search product by keyword
     * @param string $keyword Search term
     * @param string $type Response type
     * @return void
     */
    public function searchProducts(string $keyword, string $type) : void
    {
        if (empty($keyword) || strlen($keyword) < 2)
        {
            $this->sendErrorResponse("Search term be at least 2 characters", $type);
        }

        $products = $this->productModel->searchProducts($keyword);

        if (empty($products)) 
        {
            $this->sendErrorResponse("No products found matching: " . htmlspecialchars($keyword), $type);
            return;
        }

        $this->sendResponse($products, $type);
    }

    // =============================================================================================
    /**
     * Set content header type in format response
     * @param string $type Response type
     * @return void
     */
    private function setContentTypeHeader(string $type) : void
    {
        header("Content-Type: {$type}");
    }

    // =============================================================================================

}