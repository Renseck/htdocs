<?php

namespace view;

require_once 'classes/view/htmldocument.php';

use controller\sessionController;
use controller\cartController;

class cartPage extends \view\htmlDoc
{
    private $cartController;
    private $cartData;

    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Shopping cart", $pages);
        $this->setPageHeaderText("Shopping cart");
        
        // Bind JavaScript files to enable AJAX functionality
        $this->addJs("https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js");
        $this->addJs("assets/js/cart.js");

        // Initialize cart controller
        $this->cartController = new cartController();
        
        // Get cart data
        $this->cartData = $this->cartController->getCartWithDetails();
    }

    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();

        // Check if the user is logged in
        if (!sessionController::isLoggedIn())
        {
            echo '<div class="not-logged-in">' . PHP_EOL
                .'<p>Please <a href="index.php?page=login">log in</a> to view your cart.</p>' . PHP_EOL
                .'</div>' . PHP_EOL;
            return;
        }

        // Container for the shopping cart that will be updated via AJAX
        echo '<div class="shopping_cart">' . PHP_EOL;

        // Check if the cart is empty
        if (empty($this->cartData["items"])) 
        {
            echo '<div class="empty-cart-message">' . PHP_EOL
                .'<p>Your cart is empty.</p>' . PHP_EOL
                .'<p><a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a></p>' . PHP_EOL
                .'</div>' . PHP_EOL;
            return;
        }
        else
        {
            $this->displayCartContents();
        }
        
        echo '</div>' . PHP_EOL;
    }

    // =============================================================================================
    private function displayCartContents()
    {
        echo '<table class="shopping_cart">' . PHP_EOL
            .'<thead>' . PHP_EOL
            .'<tr>' . PHP_EOL
            .'<th>Product</th>'
            .'<th>Name</th>'
            .'<th>Price</th>'
            .'<th>Quantity</th>'
            .'<th>Total</th>'
            .'<th>Action</th>'
            .'</tr>' . PHP_EOL
            .'</thead>' . PHP_EOL
            .'<tbody>' . PHP_EOL;

        foreach ($this->cartData['items'] as $item) {
            // Extract product details
            $product = $item['product'];
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $product['price'];
            $itemTotal = $item['item_total'];
            
            $productName = htmlspecialchars($product['name']);
            // echo $productName;
            $productImage = !empty($product['image']) ? htmlspecialchars($product['image']) : 'placeholder.png';

            echo '<tr class="item" data-product-id="' . $productId . '">' . PHP_EOL
                .'<td><img src="assets/images/' . $productImage . '" alt="' . $productName . '"></td>' . PHP_EOL
                .'<td>' . $productName . '</td>' . PHP_EOL
                .'<td>€' . number_format((float)$price, 2) . '</td>' . PHP_EOL
                .'<td>' . PHP_EOL
                .'<input type="number" name="quantity" min="1" max="99" value="' . $quantity . '">' . PHP_EOL
                .'<button class="update-btn">Update</button>' . PHP_EOL
                .'</td>' . PHP_EOL
                .'<td>€' . $itemTotal . '</td>' . PHP_EOL
                .'<td><button class="remove-btn">❌</button></td>' . PHP_EOL
                .'</tr>' . PHP_EOL;
        }
        
        echo '</tbody>' . PHP_EOL
            .'</table>' . PHP_EOL;
        
        // Cart actions
        echo '<div class="cart-actions">' . PHP_EOL
            .'<a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a>' . PHP_EOL
            .'<button class="clear-cart-btn">Clear Cart</button>' . PHP_EOL
            .'<div class="cart-total">' . PHP_EOL
            .'<span class="cart-total-label">Total:</span>' . PHP_EOL
            .'<span class="cart-total-value">€' . number_format((float)$this->cartData['total_price'], 2) . '</span>' . PHP_EOL
            .'</div>' . PHP_EOL
            .'<form action="index.php" method="POST" class="checkout-form">' . PHP_EOL
            .'<input type="hidden" name="page" value="checkout">' . PHP_EOL
            .'<button type="submit" class="checkout-btn">Checkout</button>' . PHP_EOL
            .'</form>' . PHP_EOL
            .'</div>' . PHP_EOL;
    }

    // =============================================================================================
}
