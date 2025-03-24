<?php

namespace controller;

require_once 'classes/model/productmodel.php';

use controller\sessionController;
use model\productModel;
use model\orderModel;

class cartController
{
    private $productModel;

    // =============================================================================================
    public function __construct()
    {
        $this->productModel = new productModel();
    }

    // =============================================================================================
    /**
     * Add a product to the cart
     * @param int $productId The product ID
     * @param int $quantity The quantity to add
     * @return array Result with success status and message
     */
    public function addToCart($productId, $quantity)
    {
        // Validate product exists
        $product = $this->productModel->getProductById($productId);
        if (!$product)
        {
            return ["success" => false, "message" => "Product not found"];
        }

        // Validate quantity
        if ($quantity <= 0)
        {
            return ["success" => false, "message" => "Invalid quality"];
        }

        // Initialize cart if it doesn't exist
        if (!isset($_SESSION["cart"]))
        {
            $_SESSION["cart"] = [];
        }

        // Add or update item in cart
         if ($this->isItemInCart($productId))
         {
             $_SESSION["cart"][$productId] += $quantity;
         }
         else
         {
             $_SESSION["cart"][$productId] = $quantity;
         }

         return ["success" => true, "message" => "Item added to cart"];
    }

    // =============================================================================================
    /**
     * Update the quantity of an item in the cart
     * @param int $productId The product ID
     * @param int $quantity The quantity to update
     * @return array
     */
    public function updateCartItem($productId, $quantity)
    {
        // Check if a product is in the cart
        if (!$this->isItemInCart($productId))
        {
            return ["success" => false, "message" => "Product not found in cart"];
        }

        // Check if quantity is valid
        if ($quantity <= 0) 
        {
            return $this->removeFromCart($productId);
        }

        // Update quantity
        $_SESSION["cart"][$productId] = $quantity;

        return ["success" => true, "message" => "Cart updated"];
    }

    // =============================================================================================
    /**
     * Remove an item from the cart
     * @param int $productId The product ID
     * @return array
     */
    public function removeFromCart($productId)
    {
        // Check if item is in the cart
        if (!$this->isItemInCart($productId))
        {
            return ["success" => false, "message" => "Product not found in cart"];
        }

        // Remove from cart
        unset($_SESSION["cart"][$productId]);

        return ["success" => true, "message" => "Product removed from cart"];
    }

    // =============================================================================================
    public function getCartWithDetails()
    {
        $cart = $_SESSION["cart"] ?? [];
        $cartItems = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity)
        {
            $product = $this->productModel->getProductById($productId);

            if ($product)
            {
                $itemTotal = $product["price"] * $quantity;
                $totalPrice += $itemTotal;

                $cartItems[] = [
                    "product_id" => $productId,
                    "product" => $product,
                    "quantity" => $quantity,
                    "item_total" => $itemTotal
                ];
            }
        }

        return ["items" => $cartItems,
                "total_price" => $totalPrice,
                "item_count" => $this->getItemCount()];

    }

    // =============================================================================================
    /**
     * Get the total number of unique items in the cart
     * @return int
     */
    public function getItemCount()
    {
        return isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0;
    }

    // =============================================================================================
    /**
     * See if an item is in the cart
     * @param int $productId The product Id
     * @return bool
     */
    public function isItemInCart($productId)
    {
        return isset($_SESSION["cart"][$productId]);
    }

    // =============================================================================================
    /**
     * Clear the cart of all items
     * @return array
     */
    public function clearCart()
    {
        $_SESSION["cart"] = [];

        return ["success" => true, "message" => "Cart cleared"];
    }

    // =============================================================================================
    public function checkout()
    {
        // Ensure the user is logged in
        if (!sessionController::isLoggedIn())
        {
            return ["success" => false, "message" => "You must be logged in to checkout"];
        }

        // Get cart contents
        $cartData = $this->getCartWithDetails();

        // Check if the cart is empty
        if (empty($cartData["items"]))
        {
            return ["success" => false, "message" => "Your cart is empty"];
        }

        // Get user ID from session
        $user = sessionController::getCurrentuser();
        $userId = $user["id"];

        // Create the order model and place the order
        $orderModel = new orderModel();
        $result = $orderModel->createOrder($userId, $cartData["items"]);

        // Clear the cart
        if ($result["success"])
        {
            $this->clearCart();
        }

        return $result;
    }
}