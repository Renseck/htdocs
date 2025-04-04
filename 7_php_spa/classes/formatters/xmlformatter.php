<?php

namespace formatters;

use DOMDocument;
use interfaces\formatterInterface;

class xmlFormatter implements formatterInterface
{
    private $xsdLocation = "/6_php_rebuild/assets/xsd/product_response.xsd";

    // =============================================================================================
    public function formatResponse(array $data): string
    {
        $xml = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<response xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
            'xsi:noNamespaceSchemaLocation="' . $this->xsdLocation . '"></response>'
        );

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

        $xmlString = $xml->asXML();
        
        if(!$this->validateXml($xmlString))
        {
            return $this->formatError("Internal error: Generated XML failed validation");
        }

        return $xmlString;
    }

    // =============================================================================================
    public function formatError(string $message): string
    {
        $xml = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<response xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
            'xsi:noNamespaceSchemaLocation="' . $this->xsdLocation . '"></response>'
        );
        
        $xml->addChild("success", "false");
        $xml->addChild("message", htmlspecialchars($message));
        
        return $xml->asXML();
    }

    // =============================================================================================
    public function getContentType(): string
    {
        return "text/xml";
    }

    // =============================================================================================
    /**
     * Add product to XML format
     * @param SimpleXMLElement $node 
     * @param array $product Contains product info
     * @return void
     */
    private function addProductToXml(\SimpleXMLElement $node, array $product) : void
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

    // =============================================================================================
    /**
     * Validate XML string against XSD schema
     * @param string $xml The XML string to validate
     * 
     * @return bool Whether validation passed
     */
    private function validateXml(string $xml) : bool
    {
        libxml_use_internal_errors(true);

        $domDocument = new DOMDocument();
        $domDocument->loadXML($xml);

        $schemaPath = $_SERVER["DOCUMENT_ROOT"] . $this->xsdLocation;

        if (!file_exists($schemaPath)) {
            error_log("XML Schema file not found: " . $schemaPath);
            return false;
        }

        $isValid = $domDocument->schemaValidate($schemaPath);

        if (!$isValid) 
        {
            $errors = libxml_get_errors();
            foreach ($errors as $error) 
            {
                error_log("XML Validation Error: " . $error);
            }
            libxml_clear_errors();
        }

        return $isValid;
    }
}