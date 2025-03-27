<?php

namespace controller;

use model\ratingModel;
use controller\cartController;

class ajaxController
{
    protected $_isajax;
    protected $ratingModel;
    protected $cartController;

    // =============================================================================================
    public function __construct()
    {
        $this->ratingModel = new ratingModel();
        $this->cartController = new cartController();
        sessionController::startSession();
    }

    // =============================================================================================
    /**
     * Get server variable
     * @param string $name Name of server variable
     * @param string $default Default output
     * @return array|string Server variable or default when not found
     */
    protected function _getServerVar(string $name, string $default="<strong>NOT SET</strong>") : array|string
    {
        return $_SERVER[$name] ?? $default;
    }

    // =============================================================================================
    /**
     * Send JSON response to user
     * @param bool $success Success status
     * @param string $message Message to display
     * @param null $data Optional data to return
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
    protected function validateRequiredParams(array $required, string $method = "POST") : bool
    {
        $params =  $method === "POST" ? $_POST : $_GET;

        foreach ($required as $param) {
            if (!isset($params[$param]) || $params[$param] === '') {
                $this->sendResponse(false, "Missing required parameter: {$param}");
                return false;
            }
        }
        
        return true;
    }

    // =============================================================================================
    /**
     * Handle rating a product
     * @return void
     */
    public function rateProduct() : void
    {
        if (!$this->requireLogin())
        {
            // Gotta be logged in to rate products
            return;
        }

        if (!$this->validateRequiredParams(["product_id", "rating"]))
        {
            // Missing parameters to perform the rating
            return;
        }

        $user = sessionController::getCurrentuser();
        $userId = $user["id"];
        $productId = (int)$_POST["product_id"];
        $rating = (int)$_POST["rating"];

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
     * @return void
     */
    public function getAvgProductRating() : void
    {
        if (!$this->validateRequiredParams(["id"], "GET"))
        {
            return;
        }

        $productId = (int)$_GET["id"];
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
     * @return void
     */
    public function addToCart() : void
    {
        if (!$this->requireLogin())
        {
            return;
        }

        if (!$this->validateRequiredParams(["product_id", "quantity"]))
        {
            return;
        }

        $productId = (int)$_POST["product_id"];
        $quantity = (int)$_POST["quantity"];

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
    public function updateCartItem() : void
    {
        if (!$this->requireLogin())
        {
            return;
        }

        if (!$this->validateRequiredParams(["product_id", "quantity"]))
        {
            return;
        }

        $productId = (int)$_POST["product_id"];
        $quantity = (int)$_POST["quantity"];

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
    public function removeFromCart()
    {
        if (!$this->requireLogin())
        {
            return;
        }

        if (!$this->validateRequiredParams(["product_id", "quantity"]))
        {
            return;
        }

        $productId = (int)$_POST["product_id"];

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
     * Check whether a request is an AJAX request
     * @return bool
     */
    public static function isAjaxRequest() : bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    // =============================================================================================
    /**
     * Clear the cart
     * @return void
     */
    public function clearCart() : void
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
    public function getCartContents() : void
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