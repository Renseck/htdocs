<?php

namespace formatters;

use \interfaces\formatterInterface;

class htmlFormatter implements formatterInterface
{

    // =============================================================================================
    public function formatResponse(array $data): string
    {
        // HTML start
        $html = '<h1>Product Information</h1>' . PHP_EOL;
        // Add x product(s)
        if (isset($data["product_id"]))
        {
            // Single product
            $html .= $this->singleProductHtml($data);
        }
        else 
        {
            $html .= $this->multipleProductsHtml($data);
        }

        return $this->createHtmlDocument($html);
    }

    // =============================================================================================
    public function formatError(string $message): string
    {
        return $this->createHtmlDocument('<h1>Error</h1><p class="error">' . htmlspecialchars($message) . '</p>', 'API Error');
    }

    // =============================================================================================
    public function getContentType(): string
    {
        return "text/html";
    }

    // =============================================================================================
    /**
     * Creates a full HTML string
     * @param string $content The content to put into the <body> tags
     * @param mixed $title Web tab title
     * 
     * @return string The formatted HTML string
     */
    private function createHtmlDocument(string $content, string $title = "API Response") : string
    {
        return '<!DOCTYPE html>' . PHP_EOL
                .'<html>' . PHP_EOL
                .'<head>' . PHP_EOL
                    .'<title>' . $title . '</title>' . PHP_EOL
                    .'<style>
                        body { font-family: Arial, sans-serif; margin:20px; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                     ' . PHP_EOL . '</style>' . PHP_EOL
                .'</head>' . PHP_EOL
                .'<body>' . $content . '</body>' . PHP_EOL;
                    
    }

    // =============================================================================================
    /**
     * Adds a single product to the HTML response
     * @param array $product Product array
     * @return string HTML for single product
     */
    private function singleProductHtml(array $product) : string
    {
        $html = '<h2>Product #' . $product["product_id"] . '</h2>' . PHP_EOL
                .'<table>' . PHP_EOL
                .'<tr><th>Property</th><th>Value</th></tr>' . PHP_EOL;

        foreach ($product as $key => $value)
        {
            $html .= '<tr><td>' . htmlspecialchars($key) . '</td><td>' . htmlspecialchars($value) . '</td>' . PHP_EOL;
        }

        $html .= '</table>' . PHP_EOL;
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
            $html .= '</tr>' . PHP_EOL;

        }

        $html .= '</table>' . PHP_EOL;
        return $html;

    }
}