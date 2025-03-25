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

        // Check if the cart is empty
        if (empty($this->cartData["items"])) 
        {
            echo '<div class="empty-cart-message">' . PHP_EOL
                .'<p>Your cart is empty.</p>' . PHP_EOL
                .'<p><a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a></p>' . PHP_EOL
                .'</div>' . PHP_EOL;
            return;
        }
        
        $this->displayCartContents();
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
            $product = $item['product'];
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $product['price'];
            $itemTotal = $item['item_total'];
            
            $productName = htmlspecialchars($product['name']);
            $productImage = !empty($product['image']) ? htmlspecialchars($product['image']) : 'placeholder.png';
            
            echo '<tr>' . PHP_EOL
                .'<td class="product-image"><img src="assets/images/' . $productImage . '" alt="' . $productName . '"></td>' . PHP_EOL
                .'<td class="product-name">' . $productName . '</td>' . PHP_EOL
                .'<td class="product-price">€' . number_format($price, 2) . '</td>' . PHP_EOL
                .'<td class="product-quantity">' . PHP_EOL

                .'<form method="POST" action="index.php">' . PHP_EOL
                .'<input type="hidden" name="page" value="updatecart">' . PHP_EOL
                .'<input type="hidden" name="product_id" value="' . $productId . '">' . PHP_EOL
                .'<input type="number" name="quantity" value="' . $quantity . '" min="1" max="99" class="quantity-input">' . PHP_EOL
                .'<button type="submit" class="update-btn">Update</button>' . PHP_EOL
                .'</form>' . PHP_EOL

                .'</td>' . PHP_EOL
                .'<td class="product-total">€' . number_format($itemTotal, 2) . '</td>' . PHP_EOL
                .'<td class="product-action">' . PHP_EOL

                .'<form method="POST" action="index.php">' . PHP_EOL
                .'<input type="hidden" name="page" value="removefromcart">' . PHP_EOL
                .'<input type="hidden" name="product_id" value="' . $productId . '">' . PHP_EOL
                .'<button type="submit" class="remove-btn">❌</button>' . PHP_EOL
                .'</form>' . PHP_EOL

                .'</td>' . PHP_EOL
                .'</tr>' . PHP_EOL;
        }
        
        echo '</tbody>' . PHP_EOL
            .'<tfoot>' . PHP_EOL
            .'<tr>' . PHP_EOL
            .'<td></td><td class="cart-total-label">Total:</td>' . PHP_EOL
            .'<td></td><td></td><td class="cart-total-value">€' . number_format($this->cartData['total_price'], 2) . '</td>' . PHP_EOL
            .'</tr>' . PHP_EOL
            .'</tfoot>' . PHP_EOL
            .'</table>' . PHP_EOL
        
            .'<div class="cart-actions">' . PHP_EOL
            .'<a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a>' . PHP_EOL
            .'<form method="POST" action="index.php" class="clear-cart-form">' . PHP_EOL
            .'<input type="hidden" name="page" value="clearcart">' . PHP_EOL
            .'<button type="submit" class="clear-cart-btn">Clear Cart</button>' . PHP_EOL
            .'</form>' . PHP_EOL

            .'<form method="POST" action="index.php" class="checkout-form">' . PHP_EOL
            .'<input type="hidden" name="page" value="checkout">' . PHP_EOL
            .'<button type="submit" class="checkout-btn">Proceed to Checkout</button>' . PHP_EOL
            .'</form>' . PHP_EOL
            .'</div>' . PHP_EOL;
    }

    // =============================================================================================
}
