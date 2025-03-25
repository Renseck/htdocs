<?php

namespace view;

require_once "classes/view/htmldocument.php";

use controller\sessionController;
use model\productModel;

class productPage extends \view\htmlDoc
{
    private $productModel;
    private $product;
    private $productId;

    // =============================================================================================
    public function __construct($pages)
    {
        parent::__construct("Product details", $pages);
        
        $this->productId = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

        $this->productModel = new productModel();

        $this->product = $this->productModel->getProductById($this->productId);

        if ($this->product)
        {
            $this->title = htmlspecialchars($this->product["name"]);
            $this->setPageHeaderText($this->product["name"]);
        }
        else
        {
            $this->setPageHeaderText("Product not found");
        }
    }

    // =============================================================================================
    public function bodyContent()
    {
        parent::bodyContent();

        // Check if the product exists
        if (!$this->product)
        {
            echo '<div class="product-not-found">' . PHP_EOL
                .'<p>The requested product could not be found.</p>' . PHP_EOL
                .'<p><a href="index.php?page=webshop" class="continue-shopping">Return to Shop</a></p>' . PHP_EOL
                .'</div>' . PHP_EOL;
                return; // Knock out the function
        }

        // Display the product details
        $this->displayProductDetails();
    }

    // =============================================================================================
    private function displayProductDetails()
    {
        $productId = htmlspecialchars($this->product["product_id"]);
        $productName = htmlspecialchars($this->product["name"]);
        $productDesc = htmlspecialchars($this->product["description"]);
        $productPrice = htmlspecialchars($this->product["price"]);
        $productImage = htmlspecialchars($this->product["image"]);

        // ? Make this into a utils thing?
        // Check if the image exists
        $imagePath = "assets/images/" . $productImage;
        if (empty($productImage) || !file_exists($imagePath))
        {
            $imagePath = "assets/images/placeholder.png";
        }

        $isLoggedIn = sessionController::isLoggedIn();

         // Display product information in two columns
        echo '<div class="product_container">' . PHP_EOL
            .'<div class="img_highlight">' . PHP_EOL
            .'<img src="' . $imagePath . '" alt="' . $productName . '">' . PHP_EOL
            .'<h3>' . $productName . '</h3>' . PHP_EOL
            .'<p>' . $productDesc . '</p>' . PHP_EOL
            .'</div>' . PHP_EOL
            .'<div class="order_card">' . PHP_EOL
            .'<h3>Order now</h3>' . PHP_EOL
            .'<p class="price">€' . number_format((float)$productPrice, 2) . '</p>' . PHP_EOL;
         
     // Show add to cart form if logged in
    if ($isLoggedIn) {
        echo '<form method="POST" action="index.php" class="order-form">' . PHP_EOL
            . '<input type="hidden" name="page" value="addtocart">' . PHP_EOL
            . '<input type="hidden" name="return_to" value="product">' . PHP_EOL
            . '<input type="hidden" name="product_id" value="' . $productId . '">' . PHP_EOL
            . '<input type="number" name="quantity" min="1" max="99" value="1">' . PHP_EOL
            . '<button type="submit" class="add-to-cart-btn">Add to Cart</button>' . PHP_EOL
            . '</form>' . PHP_EOL;

    } 
    else
    {
        echo '<a href="index.php?page=login" class="login-pls-btn">Login to order</a>' . PHP_EOL;
    }
     
    echo '</div>' . PHP_EOL
        .'</div>';
    }

    // =============================================================================================
}
