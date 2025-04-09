<?php

require_once "vendor/autoload.php";

session_start();

use App\view\layout;
use App\view\homePage;
use App\view\aboutPage;
use App\view\contactPage;

function getContent($page) {
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

function processContactForm($data) {
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



$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

$page = $_GET["page"] ?? "home";

if($_SERVER["REQUEST_METHOD"] === "POST")
{
	switch($page)
	{
		case "contact":
			$contactpage = new contactPage();
			
			$result = processContactForm($_POST);

			$_SESSION["contact_result"] = $result;

			// For AJAX requests, return JSON
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit();
            }
            
            // For regular form submissions, redirect to prevent resubmission
            header('Location: index.php?page=contact');
            exit();
            break;
	}
}

if($_SERVER["REQUEST_METHOD"] === "GET")
{
	if ($isAjax)
	{
		echo getContent($page);
		exit();
	}

	$content = getContent($page);
	echo layout::render($content, "My website");
}

