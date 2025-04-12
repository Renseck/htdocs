<?php

namespace App\controllers;

use App\controllers\pageController;
use App\utils\validator;

class ajaxController extends baseController
{
    // =============================================================================================
    public function handleRequest(array $requestData)
    {
        switch ($requestData['method'])
        {
            case "GET":
                $this->handleGetRequest($requestData);
                break;
            
            case "POST":
                $this->handlePostRequest($requestData);
                break;

            default:
                $this->jsonResponse(["success" => "false", "message" => "Method handling not implemented"], $status = 404);
                break;
        }
    }

    // =============================================================================================
    private function handleGetRequest(array $requestData) : void
    {
        $pageController = new pageController();
        $content = $pageController->getContent($requestData["query"]["page"]);
        echo $content;
    }
    
    // =============================================================================================
    private function handlePostRequest(array $requestData) : void
    {
        $page = $requestData["query"]["page"];
        $data = $requestData["data"];

        switch($page) 
        {
            case "contact":
                $result = $this->processContactForm($data);
                $_SESSION["contact_result"] = $result;
                
                $this->jsonResponse($result, 200);
                break;
            
            default:
                $result = ["success" => false, "message" => "Unknown AJAX request"];
                $this->jsonResponse($result, 404);
                break;
        }
    }
    
    // =============================================================================================
    private function processContactForm(array $data) : array
    {
        // Validate form data using validator class
        // In principle, the form itself knows what the required fields are... Geert may have a point here
        $requiredFields = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ];
        
        $isValid = validator::validateRequired($data, $requiredFields);
        
        // Return errors if validation failed
        if (!$isValid) return ['success' => false, 'message' => implode("<br>", validator::getErrors())];

        // Process the form (e.g., send email, save to database)
        // Do nothing here for now
        $success = true; // Assume success for this example

        if ($success) 
        {
            return [
                'success' => true,
                'message' => "Thank you for your message! We'll get back to you soon."
            ];
        } 
        else 
        {
            return [
                'success' => false,
                'message' => "Sorry, there was a problem sending your message. Please try again."
            ];
        }
    }
}