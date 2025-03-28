<?php

namespace controller;

use model\productModel;
use SimpleXMLElement;

class apiController
{
    private $productModel;
    public $validTypes = ["json", "xml", "html"];

    // =============================================================================================
    public function __construct()
    {
        $this->productModel = new productModel();
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
        $this->formatResponse($products, $type);
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

        $this->formatResponse($product, $type);
    }

    // =============================================================================================
    /**
     * Format response in requested type
     * @param array $data Data to be formatted
     * @param string $type Response type
     * @return void
     */
    private function formatResponse(array $data, string $type) : void
    {
        switch ($type)
        {
            case "json":
                header('Content-Type: application/json');
                echo json_encode([
                    "success" => true,
                    "data" => $data
                ]);
                break;

            case "xml":
                header('Content-Type: text/xml');
                echo $this->arrayToXml($data);
                break;

            case "html":
                header('Content-Type: text/html');
                echo $this->arrayToHtml($data);
                break;
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
        switch ($type)
        {
            case "json":
                header('Content-Type: application/json');
                echo json_encode([
                    "success" => false,
                    "message" => $message
                ]);
                break;

            case "xml":
                header('Content-Type: text/xml');
                echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<response><success>false</success><message>{$message}</message></response>";
                break;

            case "html":
                header('Content-Type: text/html');
                echo "<html><body><h1>Error</h1><p>{$message}</p></body></html>";
                break;

            default:
                header('Content-Type: text/plain');
                echo "Error: {$message}";
                break;
        }
    }

    // ======================================= XML portion =========================================
    // =============================================================================================
    /**
     * Translate array data to XML format
     * @param array $data Data to translate
     * @return string XML output as string
     */
    private function arrayToXml(array $data) : string
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response></response>');
        $xml->addChild("success", "true");

        $dataNode = $xml->addChild('data');

        // Add x product(s)
        if (isset($data["product_id"]))
        {
            // Single product
            $this->addProductToXml($dataNode->addChild("product"), $data);
        }
        else 
        {
            // Multiple products
            foreach($data as $product)
            {
                $this->addProductToXml($dataNode->addChild("product"), $product);
            }
        }

        return $xml->asXML();
    }

    // =============================================================================================
    /**
     * Add product to XML format
     * @param SimpleXMLElement $node 
     * @param array $product Contains product info
     * @return void
     */
    private function addProductToXml(SimpleXMLElement $node, array $product) : void
    {
        foreach ($product as $key => $value) 
        {
            if (is_numeric($value)) {
                $node->addChild($key, $value);
            }
            else
            {
                $node->addChild($key, htmlspecialchars($value));
            }
        }
    }

    // ====================================== HTML portion =========================================
    // =============================================================================================
    /**
     * Add data to HTML response (paragraph or table)
     * @param array $data Array of data
     * @return string HTML response
     */
    private function arrayToHtml(array $data) : string
    {
        // HTML start
        $html = '<!DOCTYPE html>
                <html>
                <head>
                    <title>API Response</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin:20px; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                    </style>
                </head>
                <body>
                    <h1>Product Information</h1>';

        // Add x product(s)
        if (isset($data["id"]))
        {
            // Single product
            $html .= $this->singleProductHtml($data);
        }
        else 
        {
            $html .= $this->multipleProductsHtml($data);
        }

        $html .= '</body></html>';

        return $html;
    }

    // =============================================================================================
    /**
     * Adds a single product to the HTML response
     * @param array $product Product array
     * @return string HTML for single product
     */
    private function singleProductHtml(array $product) : string
    {
        $html = '<h2>Product #' . $product["id"] . '</h2>
                <table>
                <tr><th>Property</th><th>Value</th></tr>';

        foreach ($product as $key => $value)
        {
            $html .= '<tr><td>' . htmlspecialchars($key) . '</td><td>' . htmlspecialchars($value) . '</td>';
        }

        $html .= '</table>';
        return $html;
    }

    // =============================================================================================
    /**
     * Generate HTML table of multiple products
     * @param array $products Array of arrays with product information
     * @return string HTML table response
     */
    private function multipleProductsHtml(array $products) : string
    {
        if (empty($products))
        {
            return '<p>No products found.</p>';
        }

        // Get all possible keys so we can construct the header and add items
        $keys = [];
        foreach ($products as $product)
        {
            foreach(array_keys($product) as $key)
            {
                if (!in_array($key, $keys))
                {
                    $keys[] = $key;
                }
            }
        }

        $html = '<table><tr>';

        // Build the table header
        foreach ($keys as $key) 
        {
            $html .= "<th>" . htmlspecialchars($key) . '</th>';
        }

        $html .= '</tr>';

        // Build the table rows
        foreach ($products as $product) 
        {
            $html .= '<tr>';
            foreach ($keys as $key) 
            {
                $value = $product[$key] ?? "";
                $html .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $html .= '</tr>';

        }

        $html .= '</table>';
        return $html;

    }

    // =============================================================================================

}
