<?php

session_start();

require_once "_src/view/layout.php";

function getContent($page) {
    $content = "";
    
    switch($page) {
        case "home":
            include_once "_src/view/home.php";
            $homepage = new view\homePage();
            $content = $homepage->mainContent();
            break;
            
        case "about":
            include_once "_src/view/about.php";
            $aboutpage = new view\aboutPage();
            $content = $aboutpage->mainContent();
            break;

		case "contact":
			include_once "_src/view/contact.php";
			$contactpage = new view\contactPage();
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
			include_once "_src/view/contact.php";
			$contactpage = new view\contactPage();
			
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
	echo view\layout::render($content, "My website");
}

