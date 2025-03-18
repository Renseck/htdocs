<?php

namespace controller;

use config\pageConfig;
use controller\sessionController;
use controller\authController;
use view\homePage;

class mainController
{
    private $pages;
    private $authController;

    // =============================================================================================
    public function __construct()
    {
        $this->pages = pageConfig::getPages();
        $this->authController = new authController();
        sessionController::startSession();
    }

    // =============================================================================================
    public function handleRequest()
    {
        // First, handle any POST actions (form submissions)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
            // return; // POST handling includes redirection, so we don't continue to GET handling
        }
        
        // Then handle GET requests (page display)
        $this->handleGetRequest();
    }

    // =============================================================================================

    private function handlePostRequest()
    {
        // Determine which form was submitted based on URL parameters
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
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
                
            default:
                // Unknown action, return to the home page
                sessionController::setMessage('error', 'Unknown action');
                $_GET['page'] = 'home';
                exit;
        }

    }

    // =============================================================================================

    private function handleGetRequest()
    {
        // Get the requested page from URL parameter, default to home
        $pageName = isset($_GET['page']) ? $_GET['page'] : 'home';
        
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

    private function handleLogin()
    {
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

        if (empty($email) || empty($password))
        {
            // ERROR
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "login";
            return;
        }

        // Let the authController do the actual logging in
        $result = $this->authController->login($email, $password);

        if ($result["success"])
        {
            sessionController::setMessage("success", "Login successful");
            // Redirect to the home page - could take the user anywhere
            $_GET["page"] = "home";
        } 
        else 
        {
            sessionController::setMessage("error", $result["message"]);
            // Reload the login page for the user to try again
            $_GET["page"] = "login";
        }
    }
    // =============================================================================================

    private function handleRegistration()
    {
        $name = isset($_POST["name"]) ? $_POST["name"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        $passwordRepeat = isset($_POST["password_repeat"]) ? $_POST["password_repeat"] : "";

        if (empty($name) || empty($email) || empty($password) || empty($passwordRepeat))
        {
            // ERROR
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "register";
            return;
        }

        $result = $this->authController->register($name, $email, $password, $passwordRepeat);

        if ($result["success"])
        {
            sessionController::setMessage("success", "Registration successful");
            // Redirect to the login page - could take the user anywhere
            $_GET["page"] = "login";
        } 
        else 
        {
            sessionController::setMessage("error", $result["message"]);
            // Reload the register page for the user to try again
            $_GET["page"] = "register";
        }
    }
    // =============================================================================================

    private function handleLogout()
    {   
        sessionController::logout();
        sessionController::setMessage("success", "You have been logged out succesfully");
        // Redirect to home
        $_GET["page"] = "home";
    }
    // =============================================================================================

    private function handleContactSubmission()
    {
        $name = isset($_POST["name"]) ? $_POST["name"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $message = isset($_POST["message"]) ? $_POST["message"] : "";

        if (empty($name) || empty($email) || empty($message)) 
        {
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "contact";
            exit;
        }

        // I could in principal do some kind of input sanitisation and then yeet the message into a 
        // database, but for the purposes of this assignment i'll just knock in a success message 
        // and call it a day  
        sessionController::setMessage("success", "Thank you for contacting us");
        $_GET["page"] = "contact";
    }
    // =============================================================================================

    
    private function handleAddToCart()
    {
        // Check if the user is somehow trying to put items into the cart without being logged in
        if (!sessionController::isLoggedIn())
        {
            sessionController::setMessage("error", "Please log in to add items to the cart");
            $_GET["page"] = "login";
            exit;
        }

        $productId = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : 0;
        $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 0;

        // Check if the product id and quantity are allowable
        if ($productId <= 0 || $quantity <= 0)
        {
            sessionController::setMessage("error", "Invalid product or quantity");
            $_GET["page"] = "webshop";
            exit;
        }

        // ? Should some of the processes here be delegated to a cartController or a cartModel? // 
        // Initialize cart session if it doesn't exist yet
        if (!isset($_SESSION["cart"]))
        {
            $_SESSION["cart"] = [];
        }

        // Add or update number of items in the cart
        if (isset($_SESSION["cart"][$productId]))
        {
            $_SESSION["cart"][$productId] += $quantity;
        }
        else
        {
            $_SESSION["cart"][$productId] = $quantity;
        }

        // sessionController::setMessage("success", "Product added to cart");
        $_GET["page"] = "webshop";
    }
}
