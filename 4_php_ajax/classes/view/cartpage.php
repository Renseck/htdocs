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
            echo '<div class="not-logged-in">';
            echo '<p>Please <a href="index.php?page=login">log in</a> to view your cart.</p>';
            echo '</div>';
            return;
        }

        // Check if the cart is empty
        if (empty($this->cartData["items"])) 
        {
            echo '<div class="empty-cart-message">';
            echo '<p>Your cart is empty.</p>';
            echo '<p><a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a></p>';
            echo '</div>';
            return;
        }
        
        $this->displayCartContents();
    }

    // =============================================================================================
    private function displayCartContents()
    {
        echo '<table class="shopping_cart">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Product</th>';
        echo '<th>Name</th>';
        echo '<th>Price</th>';
        echo '<th>Quantity</th>';
        echo '<th>Total</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($this->cartData['items'] as $item) {
            $product = $item['product'];
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $product['price'];
            $itemTotal = $item['item_total'];
            
            $productName = htmlspecialchars($product['name']);
            $productImage = !empty($product['image']) ? htmlspecialchars($product['image']) : 'placeholder.png';
            
            echo '<tr>';
            echo '<td class="product-image"><img src="assets/images/' . $productImage . '" alt="' . $productName . '"></td>';
            echo '<td class="product-name">' . $productName . '</td>';
            echo '<td class="product-price">€' . number_format($price, 2) . '</td>';
            echo '<td class="product-quantity">';

            echo '<form method="POST" action="index.php">';
            echo '<input type="hidden" name="form_action" value="updatecart">';
            echo '<input type="hidden" name="product_id" value="' . $productId . '">';
            echo '<input type="number" name="quantity" value="' . $quantity . '" min="1" max="99" class="quantity-input">';
            echo '<button type="submit" class="update-btn">Update</button>';
            echo '</form>';

            echo '</td>';
            echo '<td class="product-total">€' . number_format($itemTotal, 2) . '</td>';
            echo '<td class="product-action">';

            echo '<form method="POST" action="index.php">';
            echo '<input type="hidden" name="form_action" value="removefromcart">';
            echo '<input type="hidden" name="product_id" value="' . $productId . '">';
            echo '<button type="submit" class="remove-btn">❌</button>';
            echo '</form>';

            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '<tfoot>';
        echo '<tr>';
        echo '<td></td><td class="cart-total-label">Total:</td>';
        echo '<td></td><td></td><td class="cart-total-value">€' . number_format($this->cartData['total_price'], 2) . '</td>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';
        
        echo '<div class="cart-actions">';
        echo '<a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a>';
        echo '<form method="POST" action="index.php" class="clear-cart-form">';
        echo '<input type="hidden" name="form_action" value="clearcart">';
        echo '<button type="submit" class="clear-cart-btn">Clear Cart</button>';
        echo '</form>';

        echo '<form method="POST" action="index.php" class="checkout-form">';
        echo '<input type="hidden" name="form_action" value="checkout">';
        echo '<button type="submit" class="checkout-btn">Proceed to Checkout</button>';
        echo '</form>';
        echo '</div>';
    }

    // =============================================================================================
}
