<?php

$baseUrl = 'http://localhost/5_php_api/index.php?action=api';

// =================================================================================================
function callApi($url) 
{
    $response = file_get_contents($url);
    if ($response === false)
    {
        return "Error: unable to fetch data from API";
    }

    return $response;
}

// =================================================================================================
function displayResponse($title, $url, $response)
{
    echo "<h2>{$title}</h2>"
        ."<p>URL: <a href='{$url}' target='_blank'>{$url}</a></p>";

    // Determine response type
    if (strpos($url, 'type=json') !== false)
    {
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
        $jsonData = json_decode($response, true);
        if ($jsonData !== null)
        {
            echo "<h3>Formatted JSON:</h3>"
                ."<pre>" . htmlspecialchars(json_encode($jsonData, JSON_PRETTY_PRINT)) . "</pre>";
        }
    }
    else
    {
        echo $response;
    }

    echo "<hr>";
}

// =================================================================================================
function getHttpResponseCode($url)
{
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

// =================== Not gonna worry about making this with proper functions =====================
echo '<!DOCTYPE html>' . PHP_EOL
    .'<html>' . PHP_EOL
    .'<head>' . PHP_EOL
        .'<title>API Test</title>' . PHP_EOL
        .'<style>' . PHP_EOL
            .'body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }' . PHP_EOL
            .'h1 { color: #333; }'  . PHP_EOL
            .'h2 { color: #555; margin-top: 30px; }' . PHP_EOL
            .'pre { background-color: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }' . PHP_EOL
            .'hr { margin: 30px 0; border: 0; border-top: 1px solid #ddd; }' . PHP_EOL
            .'a { color: #0066cc; }' . PHP_EOL
        .'</style>' . PHP_EOL
    .'</head>' . PHP_EOL
    .'<body>' . PHP_EOL
        .'<h1>API Test Results</h1>' . PHP_EOL
    
        .'<h2>Available Endpoints</h2>' . PHP_EOL
        .'<ul>' . PHP_EOL
            .'<li>Get all products: <code>?action=api&type=[json|xml|html]&function=all</code></li>' . PHP_EOL
            .'<li>Get product by ID: <code>?action=api&type=[json|xml|html]&function=item&id=[product_id]</code></li>' . PHP_EOL
        .'</ul>' . PHP_EOL;
    
    // Test 1: Get all products in JSON format
    $url = $baseUrl . '&type=json&function=all';
    $response = callApi($url);
    displayResponse("All Products (JSON)", $url, $response);
    
    // Test 2: Get all products in XML format
    $url = $baseUrl . '&type=xml&function=all';
    $response = callApi($url);
    displayResponse("All Products (XML)", $url, $response);
    
    // Test 3: Get all products in HTML format
    $url = $baseUrl . '&type=html&function=all';
    $response = callApi($url);
    displayResponse("All Products (HTML)", $url, $response);
    
    // Test 4: Get single product in JSON format
    $url = $baseUrl . '&type=json&function=item&id=3';
    $response = callApi($url);
    displayResponse("Single Product ID 3 (JSON)", $url, $response);
    
    // Test 5: Get single product in XML format
    $url = $baseUrl . '&type=xml&function=item&id=3';
    $response = callApi($url);
    displayResponse("Single Product ID 3 (XML)", $url, $response);

    // Test 6: Get single product in HTML format BY KEYWORD
    $url = $baseUrl . '&type=html&function=item&search=stoel';
    $response = callApi($url);
    displayResponse("Single Product keyword 'stoel' (HTML)", $url, $response);
    
    // Test 7: Error case - invalid product ID
    $url = $baseUrl . '&type=json&function=item&id=999999';
    $response = callApi($url);
    displayResponse("Invalid Product ID 999999 (JSON)", $url, $response);
    
echo '</body>' . PHP_EOL
    .'</html>' . PHP_EOL;
