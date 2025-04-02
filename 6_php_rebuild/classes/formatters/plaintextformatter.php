<?php

namespace formatters;

use \interfaces\formatterInterface;

class plaintextFormatter implements formatterInterface
{

    // =============================================================================================
    public function formatResponse(array $data): string
    {
        $output = "API RESPONSE - SUCCESS\n";
        $output .= "======================\n\n";
        
        // Check if single product (has product_id key) or multiple products
        if (isset($data['product_id'])) {
            $output .= $this->formatSingleProduct($data);
        } else {
            $output .= $this->formatMultipleProducts($data);
        }
        
        return $output;
    }

    // =============================================================================================
    public function formatError(string $message): string
    {
        return "API RESPONSE - ERROR\n" .
        "====================\n\n" .
        "Error message: " . $message . "\n";
    }

    // =============================================================================================
    public function getContentType(): string
    {
        return "text/plain";
    }

    // =============================================================================================
    /**
     * Format a single product for plaintext output
     * @param array $product Product data
     * @return string Formatted output
     */
    private function formatSingleProduct(array $product): string
    {
        $output = "PRODUCT DETAILS\n";
        $output .= "--------------\n";
        
        foreach ($product as $key => $value) 
        {
            $output .= sprintf("%-20s: %s\n", ucfirst($key), $value);
        }
        
        return $output;
    }
    
    // =============================================================================================
    /**
     * Format multiple products for plaintext output
     * @param array $products Array of product data
     * @return string Formatted output
     */
    private function formatMultipleProducts(array $products): string
    {
        if (empty($products)) 
        {
            return "No products found.\n";
        }
        
        $output = "PRODUCT LIST\n";
        $output .= "------------\n";
        $output .= sprintf("Total products: %d\n\n", count($products));
        
        foreach ($products as $index => $product) 
        {
            $output .= sprintf("PRODUCT #%d\n", $index + 1);
            $output .= "---------\n";
            
            foreach ($product as $key => $value) 
            {
                $output .= sprintf("%-20s: %s\n", ucfirst($key), $value);
            }
            
            $output .= "\n";
        }
        
        return $output;
    }

}