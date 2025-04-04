<?php

namespace controller;

use controller\sessionController;
use factories\factoryManager;

class postController
{

    // =============================================================================================
    /**
     * Handle a POST request with the provided data
     * @param string $page The type of POST page (login, register, etc.)
     * @param array $data Associative array of POST data to complete the request
     * 
     * @return void
     */
    public function handleRequest(array $data) : void
    {
        $page = $data["page"];
        $formData = $data["formData"];

        switch ($page)
        {
            case 'login':
                $this->handleLogin(
                    $formData["email"] ?? "",
                    $formData["password"] ?? ""
                );
                break;
                
            case 'register':
                $this->handleRegistration(
                    $formData["name"] ?? "",
                    $formData["email"] ?? "",
                    $formData["password"] ?? "",
                    $formData["password_repeat"]
                );
                break;
                
            case 'logout':
                $this->handleLogout();
                break;

            case 'contact':
                $this->handleContactSubmission(
                    $formData["name"],
                    $formData["email"],
                    $formData["message"]
                );
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
    /**
     * Handle user login
     * @param string $email User email
     * @param string $password User password
     * 
     * @return void
     */
    private function handleLogin(string $email, string $password) : void
    {
        if (empty($email) || empty($password))
        {
            // ERROR
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "login";
            return;
        }

        $controllerFactory = factoryManager::getInstance()->getFactory("controller");
        $authController = $controllerFactory->create("auth");
        $result = $authController->login($email, $password);

        // Parse the result 
        sessionController::parseResult($result, $successPage = "home", $errorPage = "login");
    }

    // =============================================================================================
    /**
     * Handle user registration
     * @param string $name User name
     * @param string $email User email
     * @param string $password User password
     * @param string $passwordRepeat Password confirmation
     * 
     * @return void
     */
    private function handleRegistration(string $name, string $email, string $password, string $passwordRepeat) : void
    {
        if (empty($name) || empty($email) || empty($password) || empty($passwordRepeat))
        {
            // ERROR
            sessionController::setMessage("error", "Please fill in all fields");
            $_GET["page"] = "register";
            return;
        }

        $controllerFactory = factoryManager::getInstance()->getFactory("controller");
        $authController = $controllerFactory->create("auth");
        $result = $authController->register($name, $email, $password, $passwordRepeat);

        // Parse the result 
        sessionController::parseResult($result, $successPage = "login", $errorPage = "register");
    }

    // =============================================================================================
    /**
     * Handle user logout
     * @return void
     */
    private function handleLogout() : void
    {   
        sessionController::logout();
        sessionController::setMessage("success", "You have been logged out succesfully");
        // Redirect to home
        $_GET["page"] = "home";
    }

    // =============================================================================================
    /**
     * Handle contact submission
     * @param string $name User name
     * @param string $email User email
     * @param string $message User message
     * 
     * @return void
     */
    private function handleContactSubmission(string $name, string $email, string $message) : void
    {
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
    /**
     * Process checkout of cart
     * @return void
     */
    private function handleCheckout() : void
    {
        if (!sessionController::isLoggedIn())
        {
            sessionController::setMessage("error", "Please log in to checkout");
            $_GET["page"] = "login";
            return;
        }

        $controllerFactory = factoryManager::getInstance()->getFactory("controller");
        $cartController = $controllerFactory->create("cart");
        $result = $cartController->checkout();

        if ($result["success"])
        {
            $_SESSION["last_order_id"] = $result["order_id"];
        }

        sessionController::parseResult($result, "confirmation", "cart");
    }

    // =============================================================================================
}