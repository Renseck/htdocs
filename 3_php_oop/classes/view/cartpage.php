<?php

namespace view;

require_once 'classes/view/htmldocument.php';
require_once 'classes/model/productmodel.php';

use controller\sessionController;
use model\productModel;

class cartPage extends \view\htmlDoc
{
    private $productModel;
    private $cart;
    private $products = [];

    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Shopping cart", $pages);
        $this->setPageHeaderText("Shopping cart");

        // Init cart data
        $this->productModel = new productModel();
        $this->cart = $_SESSION["cart"] ?? [];

        // Get product info for every item in the cart
        if (!empty($this->cart))
        {
            foreach(array_keys($this->cart) as $productId)
            {
                $product = $this->productModel->getProductById($productId);
                if ($product)
                {
                    $this->products[$productId] = $product;
                }
            }
        }
    }

    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();

        if (empty($this->cart)) {
            echo '<div class="empty-cart">';
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
        
        $totalPrice = 0;

        foreach ($this->products as $productId => $product) {
            $quantity = $this->cart[$productId];
            $price = $product['price'];
            $itemTotal = $quantity * $price;
            $totalPrice += $itemTotal;
            
            $imagePath = "assets/images/" . htmlspecialchars($product['image']);
            if (!file_exists($imagePath)) {
                $imagePath = "assets/images/placeholder.png";
            }
            
            echo '<tr class="item">';
            echo '<td><img src="' . $imagePath . '" alt="' . htmlspecialchars($product['name']) . '"></td>';
            echo '<td>' . htmlspecialchars($product['name']) . '</td>';
            echo '<td>€' . number_format($price, 2) . '</td>';
            echo '<td>';
            echo '<form method="POST" action="index.php?page=cart&action=updatecart">';
            echo '<input type="hidden" name="product_id" value="' . $productId . '">';
            echo '<input type="number" name="quantity" value="' . $quantity . '" min="1" max="99">';
            echo '<button type="submit" class="update-btn">Update</button>';
            echo '</form>';
            echo '</td>';
            echo '<td>€' . number_format($itemTotal, 2) . '</td>';
            echo '<td>';
            echo '<form method="POST" action="index.php?page=cart&action=removefromcart">';
            echo '<input type="hidden" name="product_id" value="' . $productId . '">';
            echo '<button type="submit" class="remove-btn">×</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '<tfoot>';
        echo '<tr>';
        echo '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>';
        echo '<td colspan="2">€' . number_format($totalPrice, 2) . '</td>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';
        
        echo '<div class="cart-checkout">';
        echo '<a href="index.php?page=cart&action=checkout" class="checkout-btn">Checkout</a>';
        echo '</div>';
    }

    // =============================================================================================
}
