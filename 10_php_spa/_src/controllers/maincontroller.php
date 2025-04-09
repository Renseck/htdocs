<?php

namespace App\controllers;

use App\views\layout;
use App\views\homePage;
use App\views\aboutPage;
use App\views\contactPage;
use App\controllers\sessionController;

class mainController
{
    public function __construct()
    {
        sessionController::startSession();
    }

    // =============================================================================================
    public function handleRequest()
    {
        $requestData = $this->getRequestData();
        $isAjax = $this->isAjaxRequest();
        
        if($requestData['method'] === "POST") {
            return $this->handlePostRequest($requestData['page'], $requestData['data'], $isAjax);
        } else {
            return $this->handleGetRequest($requestData['page'], $isAjax);
        }
    }

    // =============================================================================================
    public function getRequestData()
    {
        return [
            'method' => $_SERVER["REQUEST_METHOD"],
            'page' => $_GET["page"] ?? "home",
            'data' => $_POST
        ];
    }

    // =============================================================================================
    private function isAjaxRequest() : bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    // =============================================================================================
    private function handleGetRequest($page, $isAjax)
    {
        $content = $this->getContent($page);
        
        if ($isAjax) {
            echo $content;
            exit();
        }
        
        echo layout::render($content, "My website");
    }
    
    // =============================================================================================
    private function handlePostRequest($page, $data, $isAjax)
    {
        $result = [];
        
        switch($page) {
            case "contact":
                $result = $this->processContactForm($data);
                $_SESSION["contact_result"] = $result;
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                    exit();
                }
                
                // For regular form submissions, redirect to prevent resubmission
                header('Location: index.php?page=contact');
                exit();
                break;
            
            default:
                // Handle other POST requests here
                break;
        }
    }
    
    // =============================================================================================
    private function getContent($page)
    {
        $content = "";
        
        switch($page) {
            case "home":
                $homepage = new homePage();
                $content = $homepage->mainContent();
                break;
                
            case "about":
                $aboutpage = new aboutPage();
                $content = $aboutpage->mainContent();
                break;

            case "contact":
                $contactpage = new contactPage();
                $content = $contactpage->mainContent();
                break;

            default:
                $content = "<p>Page not found</p>";
        }
        
        return $content;
    }
    
    // =============================================================================================
    private function processContactForm($data)
    {
        // Validate form data
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = "Name is required";
        }
        
        if (empty($data['email'])) {
            $errors[] = "Email is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is invalid";
        }
        
        if (empty($data['message'])) {
            $errors[] = "Message is required";
        }
        
        // Return errors if validation failed
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => implode("<br>", $errors)
            ];
        }

        // Process the form (e.g., send email, save to database)
        // Do nothing here for now
        $success = true; // Assume success for this example

        if ($success) {
            return [
                'success' => true,
                'message' => "Thank you for your message! We'll get back to you soon."
            ];
        } else {
            return [
                'success' => false,
                'message' => "Sorry, there was a problem sending your message. Please try again."
            ];
        }
    }
}