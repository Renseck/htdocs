<?php

namespace controller;

use config\pageConfig;
use controller\sessionController;
use controller\authController;
use controller\cartController;
use controller\ajaxController;
use controller\apiController;
use view\homePage;

class mainController
{
    private $pages;
    private $authController;
    private $cartController;

    // =============================================================================================
    public function __construct()
    {
        $this->pages = pageConfig::getPages();
        sessionController::startSession();
    }

    // =============================================================================================
    public function handleRequest() : void
    {
        // Check if this is an API request
        if (isset($_GET["action"]) && $_GET["action"] === "api")
        {
            $this->handleApiRequest();
            return;
        }

        // Check if this is an AJAX request
        if (ajaxController::isAjaxRequest())
        {
            $this->handleAjaxRequest();
            return;
        }

        // Then handle any POST actions (form submissions)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        }
        
        // Then handle GET requests (page display)
        $this->handleGetRequest();
    }

    // =============================================================================================
    private function handlePostRequest() : void
    {
        // Determine which form was submitted based on URL parameters
        $page = $_POST['page'] ?? '';
        
        switch ($page) 
        {
            case 'login':
                $this->handleLogin();
                break;
                
            case 'register':
                $this->handleRegistration();
                break;
                
            case 'logout':
                $this->handleLogout();
                break;

            case 'contact':
                $this->handleContactSubmission();
                break;

            case 'addtocart':
                $this->handleAddToCart();
                break;

            case 'updatecart':
                $this->handleUpdateCart();
                break;

            case 'removefromcart':
                $this->handleRemoveFromCart();
                break;

            case 'clearcart':
                $this->handleClearCart();
                break;

            case 'checkout':
                $this->handleCheckout();
                break;
                
            default:
                // Unknown action, return to the home page
                sessionController::setMessage('error', 'Unknown action');
                $_GET['page'] = 'home';
                return;
        }

    }

    // =============================================================================================
    private function handleGetRequest() : void
    {
        // Get the requested page from URL parameter, default to home
        $pageName = $_GET['page'] ?? 'home';
        
        // Check if the requested page is allowed
        if (!isset($this->pages[$pageName])) {
            $pageName = 'home';
        }
        
        // Get the class name for the requested page and show it
        $pageClass = $this->pages[$pageName];
        $page = new $pageClass($this->pages);
        $page->show();
    }

    // =============================================================================================
    private function handleAjaxRequest() : void
    {
        // Finally we can use my beloved "action" keyword
        $action = $_GET["action"] ?? "";

        $ajaxController = new ajaxController();

        switch($action)
        {
            case "rateProduct":
                $ajaxController->rateProduct();
                break;

            case "getAvgProductRating":
                $ajaxController->getAvgProductRating();
                break;

            case "addToCart":
                $ajaxController->addToCart();
                break;

            case "updateCartItem":
                $ajaxController->updateCartItem();
                break;
            
            case "removeFromCart":
                $ajaxController->removeFromCart();
                break;

            case "clearCart":
                $ajaxController->clearCart();
                break;

            case "getCartContents":
                $ajaxController->getCartContents();
                break;

            default:
                // Unknown AJAX action
                echo json_encode([
                    "success" => false,
                    "message" => "Unknown AJAX action " . $action
                ]);
                return;
        }
    }

    // =============================================================================================
    private function handleApiRequest() : void
    {
        $apiController = new apiController();

        // Get the requested function and type
        $function = $_GET['function'] ?? 'all';
        $type = $_GET["type"] ?? "json";

        // Validate type
        if (!in_array($type, $apiController->validTypes))
        {
            $apiController->sendErrorResponse("Invalid response type", $type);
            return;
        }

        // Call the appropriate function
        switch ($function)
        {
            case "all":
                $apiController->getAllProducts($type);
                break;

            case "item":
                if (isset($_GET["id"]) && is_numeric($_GET["id"]))
                {
                    $id = (int)$_GET["id"];
                    $apiController->getProductById($id, $type);
                }
                elseif (isset($_GET["search"]))
                {
                    $keyword = $_GET["search"];
                    $apiController->searchProducts($keyword, $type);
                }
                else
                {
                    $apiController->sendErrorResponse("Missing required parameter: id or search", $type);
                }
                
                break;

            default:
                $apiController->sendErrorResponse("Unknown function", $type);
                break;
        }
    }

    // =============================================================================================
    private function handleLogin() : void
    {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        if (empty($email) || empty($password))
        {
            // ERROR
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "login";
            return;
        }

        $this->authController = new authController();
        // Let the authController do the actual logging in
        $result = $this->authController->login($email, $password);

        // Parse the result 
        sessionController::parseResult($result, $successPage = "home", $errorPage = "login");
    }

    // =============================================================================================
    private function handleRegistration() : void
    {
        $name = $_POST["name"] ?? "";
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";
        $passwordRepeat = $_POST["password_repeat"] ?? "";

        if (empty($name) || empty($email) || empty($password) || empty($passwordRepeat))
        {
            // ERROR
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "register";
            return;
        }

        $this->authController = new authController();
        $result = $this->authController->register($name, $email, $password, $passwordRepeat);

        // Parse the result 
        sessionController::parseResult($result, $successPage = "login", $errorPage = "register");
    }

    // =============================================================================================
    private function handleLogout() : void
    {   
        sessionController::logout();
        sessionController::setMessage("success", "You have been logged out succesfully");
        // Redirect to home
        $_GET["page"] = "home";
    }

    // =============================================================================================
    private function handleContactSubmission() : void
    {
        $name = $_POST["name"] ?? "";
        $email = $_POST["email"] ?? "";
        $message = $_POST["message"] ?? "";

        if (empty($name) || empty($email) || empty($message)) 
        {
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "contact";
            return;
        }

        // I could in principal do some kind of input sanitisation and then yeet the message into a 
        // database, but for the purposes of this assignment i'll just knock in a success message 
        // and call it a day  
        sessionController::setMessage("success", "Thank you for contacting us");
        $_GET["page"] = "contact";
    }

    // =============================================================================================
    private function handleAddToCart() : void
    {
        // Check if the user is somehow trying to put items into the cart without being logged in
        if (!sessionController::isLoggedIn())
        {
            sessionController::setMessage("error", "Please log in to add items to the cart");
            $_GET["page"] = "login";
            return;
        }

        $productId = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : 0;
        $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 0;

        $returnPage = $_POST["return_to"] ?? "webshop";
        if ($returnPage === "product" && isset($_POST["product_id"]))
        {
            // Pass the product ID from POST to GET to ensure proper rerouting
            $_GET["id"] = $_POST["product_id"];
        }

        $this->cartController = new cartController();
        $result = $this->cartController->addToCart($productId, $quantity);

        // Parse the result 
        sessionController::parseResult($result, $successPage = $returnPage, $errorPage = $returnPage);
    }

    // =============================================================================================
    private function handleUpdateCart() : void
    {
        if (!sessionController::isLoggedIn())
        {
            sessionController::setMessage("error", "Please log in to update your cart");
            $_GET["page"] = "login";
            return;
        }

        $productId = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : 0;
        $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 0;

        $this->cartController = new cartController();
        $result = $this->cartController->updateCartItem($productId, $quantity);

        // Parse the result 
        sessionController::parseResult($result, $successPage = "cart", $errorPage = "cart");
    }

    // =============================================================================================
    private function handleRemoveFromCart() : void
    {
        if (!sessionController::isLoggedIn())
        {
            sessionController::setMessage("error", "Please log in to update your cart");
            $_GET["page"] = "login";
            return;
        }

        $productId = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : 0;

        $this->cartController = new cartController();
        $result = $this->cartController->removeFromCart($productId);

        // Parse the result 
        sessionController::parseResult($result, $successPage = "cart", $errorPage = "cart");
    }

    // =============================================================================================
    private function handleClearCart() : void
    {
        if (!sessionController::isLoggedIn())
        {
            sessionController::setMessage("error", "Please log in to update your cart");
            $_GET["page"] = "login";
            return;
        }

        $this->cartController = new cartController();
        $result = $this->cartController->clearCart();

        // Parse the result 
        sessionController::parseResult($result, $successPage = "cart", $errorPage = "cart");
    }

    // =============================================================================================
    private function handleCheckout() : void
    {
        if (!sessionController::isLoggedIn())
        {
            sessionController::setMessage("error", "Please log in to update your cart");
            $_GET["page"] = "login";
            return;
        }

        $this->cartController = new cartController();
        $result = $this->cartController->checkout();

        if ($result["success"])
        {
            $_SESSION["last_order_id"] = $result["order_id"];
        }

        // Parse the result 
        sessionController::parseResult($result, $successPage = "confirmation", $errorPage = "cart");
    }

    // =============================================================================================
}
