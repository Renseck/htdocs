<?php

namespace controller;

use factories\factoryManager;

class ajaxController
{
    protected $ratingModel;
    protected $cartController;

    // =============================================================================================
    public function __construct()
    {
        $factoryManager = factoryManager::getInstance();
        $this->ratingModel = $factoryManager->getFactory("model")->create("rating");
        $this->cartController = $factoryManager->getFactory("controller")->create("cart");
        sessionController::startSession();
    }

    // =============================================================================================
    public function handleRequest(array $data) : void
    {
        $action = $data['action'] ?? "";
        
        switch($action) {
            case "rateProduct":
                $this->rateProduct($data);
                break;
                
            case "getAvgProductRating":
                $this->getAvgProductRating($data);
                break;
                
            case "addToCart":
                $this->addToCart($data);
                break;
                
            case "updateCartItem":
                $this->updateCartItem($data);
                break;
            
            case "removeFromCart":
                $this->removeFromCart($data);
                break;
                
            case "clearCart":
                $this->clearCart($data);
                break;
                
            case "getCartContents":
                $this->getCartContents($data);
                break;
                
            default:
                // Unknown AJAX action
                echo json_encode([
                    "success" => false,
                    "message" => "Unknown AJAX action " . $action
                ]);
                break;
        }
    }

    // =============================================================================================
    /**
     * Send JSON response to user
     * @param bool $success Success status
     * @param string $message Message to display
     * @param null $data Optional data to return
     * 
     * @return void
     */
    protected function sendResponse(bool $success, string $message = '', $data = null) : void
    {
        $response = [
            "success" => $success,
            "message" => $message,
            "data" => $data
        ];

        echo json_encode($response);
        exit;
    }

    // =============================================================================================
    /**
     * Verify if user is logged in, send error response if not
     * 
     * @return bool True if user is logged in
     */
    protected function requireLogin() : bool
    {
        if (!sessionController::isLoggedIn())
        {
            $this->sendResponse(false, "You must be logged in to perform this action");
            return false;
        }
        
        return true;
    }

    // =============================================================================================
    protected function validateRequiredParams(array $requiredParams, array $data) : bool
    {
        foreach ($requiredParams as $param) {
            if (!isset($data[$param]) || $data[$param] === '') {
                $this->sendResponse(false, "Missing required parameter: {$param}");
                return false;
            }
        }
        
        return true;
    }

    // =============================================================================================
    /**
     * Handle rating a product
     * @param array $data Request data including product_id and rating
     * 
     * @return void
     */
    public function rateProduct(array $data) : void
    {
        if (!$this->requireLogin())
        {
            // Gotta be logged in to rate products
            return;
        }

        if (!$this->validateRequiredParams(["product_id", "rating"], $data))
        {
            // Missing parameters to perform the rating
            return;
        }

        $user = sessionController::getCurrentuser();
        $userId = $user["id"];
        $productId = (int)$data["product_id"];
        $rating = (int)$data["rating"];

        $result = $this->ratingModel->rateProduct($productId, $userId, $rating);

        // Convert the boolean or integer result to the expected format
        $success = $result !== false;
        $message = $success ? "Rating saved successfully" : "Failed to save rating";

        $this->sendResponse(
            $success,
            $message,
            ["rating" => $rating]
        );
    }

    // =============================================================================================
    /**
     * Handle getting average product rating information
     * @param array $data Request data including product_id and rating
     * 
     * @return void
     */
    public function getAvgProductRating(array $data) : void
    {
        if (!$this->validateRequiredParams(["id"], $data))
        {
            return;
        }

        $productId = (int)$data["id"];
        $rating = $this->ratingModel->getAverageRating($productId);

        // If the user is logged in, also get their individual rating
        $userRating = null;
        if (sessionController::isLoggedIn())
        {
            $user = sessionController::getCurrentuser();
            $userId = $user["id"];
            $userRating = $this->ratingModel->getUserRating($productId, $userId);
        }

        $this->sendResponse(
            true,
            '',
            [
                "average" => $rating["average"],
                "count" => $rating["count"],
                "userRating" => $userRating
            ]
            );
    }

    // =============================================================================================
    /**
     * Add item to cart via Ajax
     * @param array $data Request data including product_id and quantity
     * 
     * @return void
     */
    public function addToCart(array $data) : void
    {
        if (!$this->requireLogin())
        {
            return;
        }

        if (!$this->validateRequiredParams(["product_id", "quantity"], $data))
        {
            return;
        }

        $productId = (int)$data["product_id"];
        $quantity = (int)$data["quantity"];

        $result = $this->cartController->addToCart($productId, $quantity);

        // Get the updated cart to send along with the response so we can actually redraw it
        $cartSummary = $this->cartController->getCartWithDetails();

        $this->sendResponse(
            $result["success"],
            $result["message"],
            ["cart" => $cartSummary]
        );

    }

    // =============================================================================================
    /**
     * Update cart item quantity
     * @param array $data Request data including product_id and quantity
     * 
     * @return void
     */
    public function updateCartItem(array $data) : void
    {
        if (!$this->requireLogin())
        {
            return;
        }

        if (!$this->validateRequiredParams(["product_id", "quantity"], $data))
        {
            return;
        }

        $productId = (int)$data["product_id"];
        $quantity = (int)$data["quantity"];

        $result = $this->cartController->updateCartItem($productId, $quantity);
        
        // Get the updated cart to send along with the response so we can actually redraw it
        $cartSummary = $this->cartController->getCartWithDetails();

        $this->sendResponse(
            $result["success"],
            $result["message"],
            ["cart" => $cartSummary]
        );

    }

    // =============================================================================================
    /**
     * Remove item from cart
     * @param array $data Request data including product_id and rating
     * 
     * @return void
     */
    public function removeFromCart(array $data) : void
    {
        if (!$this->requireLogin())
        {
            return;
        }

        if (!$this->validateRequiredParams(["product_id"], $data))
        {
            return;
        }

        $productId = (int)$data["product_id"];

        $result = $this->cartController->removeFromCart($productId);
        
        // Get the updated cart to send along with the response so we can actually redraw it
        $cartSummary = $this->cartController->getCartWithDetails();

        $this->sendResponse(
            $result["success"],
            $result["message"],
            ["cart" => $cartSummary]
        );
    }

    // =============================================================================================
    /**
     * Clear the cart
     * @param array $data Request data (not used)
     * 
     * @return void
     */
    public function clearCart(array $data) : void
    {
        if (!$this->requireLogin())
        {
            return;
        }
        
        $result = $this->cartController->clearCart();

        $cartSummary = $this->cartController->getCartWithDetails();

        $this->sendResponse(
            $result["success"],
            $result["message"],
            ["cart" => $cartSummary]
        );
    }

    // =============================================================================================
    /**
     * Get cart contents
     * @param array $data Request data (not used)
     * 
     * @return void
     */
    public function getCartContents(array $data) : void
    {
        if (!$this->requireLogin())
        {
            return;
        }

        $cartSummary = $this->cartController->getCartWithDetails();

        $this->sendResponse(
            true,
            "Cart contents retrieved",
            ["cart" => $cartSummary]
        );
    }
}