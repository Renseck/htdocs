<?php

namespace view;

require_once 'classes/view/htmldocument.php';
require_once 'classes/model/productmodel.php';

use controller\sessionController;
use model\productModel;

class webshopPage extends \view\htmlDoc
{
    private $productModel;
    private $products;

    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Webshop", $pages);
        $this->setPageHeaderText("Webshop");

        // Add JS files
        $this->addJs("https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js");
        $this->addJs("assets/js/cart.js");

        $this->productModel = new productModel();

        // Check if we have a search query
        if (isset($_GET["search"]) && !empty($_GET["search"])) 
        {
            $keyword = $_GET["search"];
            $this->products = $this->productModel->searchProducts($keyword);
            $this->setPageHeaderText("Search results for " . htmlspecialchars($keyword));
        }
        else // If not, show all products as normal
        {
            $this->products = $this->productModel->getAllProducts();
        }
    }

    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();
        // Display search form
        $this->displaySearchForm();
        
        // Check if we have products
        if (empty($this->products)) {
            echo '<p class="no-results">No products found.</p>' . PHP_EOL;
            return;
        }
        
        // Display products grid
        $this->displayProductGrid();
    }
    
    // =============================================================================================
    private function displaySearchForm()
    {
        echo '<div class="search-container">' . PHP_EOL
            . '<form method="GET" action="index.php">' . PHP_EOL
            . '<input type="hidden" name="page" value="webshop">'. PHP_EOL
            . '<input type="text" name="search" class="search-input" placeholder="Search products..." '
            . 'value="' . (isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '') . '">' . PHP_EOL
            . '<button type="submit" class="search-button">Search</button>' . PHP_EOL
            . '</form>' . PHP_EOL
            . '</div>' . PHP_EOL;
    }

    // =============================================================================================

    private function displayProductGrid()
    {
        echo '<div class="webshop">' . PHP_EOL;

        foreach ($this->products as $product)
        {
            $this->displayProductCard($product);
        }
        
        echo "</div>" . PHP_EOL ;
    }

    // =============================================================================================

    private function displayProductCard($product)
    {
        $isLoggedIn = sessionController::isLoggedIn();
        $productId = htmlspecialchars($product["product_id"]);
        $productName = htmlspecialchars($product["name"]);
        $productDesc = htmlspecialchars($product["description"]);
        $productPrice = htmlspecialchars($product["price"]);
        $productImage = htmlspecialchars($product["image"]);

        // If no image is provided, default to the placeholder image
        if (empty($productImage) || !file_exists('assets/images/' . $productImage . ''));
        {
            $productImage = "placeholder.png";
        }

        echo '<div class="product">' . PHP_EOL
            . '<a href="index.php?page=product&id=' . $productId . '">' 
            . '<img src="assets/images/' . $productImage . '" alt="' . $productName . '">'
            . '</a>' . PHP_EOL
            . '<h3><a href="index.php?page=product&id=' . $productId . '">' . $productName . '</a></h3>' . PHP_EOL
            . '<p class="price">€' . $productPrice . '</p>' . PHP_EOL;

        // If the user is logged in, show the add to cart button - log in please otherwise
        if ($isLoggedIn)
        {
            echo '<form method="POST" action="index.php" class="order-form">' . PHP_EOL
                . '<input type="hidden" name="page" value="addtocart">' . PHP_EOL
                . '<input type="hidden" name="return_to" value="webshop">' . PHP_EOL
                . '<input type="hidden" name="product_id" value="' . $productId . '">' . PHP_EOL
                . '<input type="number" name="quantity" min="1" max="99" value="1">' . PHP_EOL
                . '<button type="submit" class="add-to-cart-btn">Add to Cart</button>' . PHP_EOL
                . '</form>' . PHP_EOL;
        } 
        else 
        {
            echo '<a href="index.php?page=login" class="login-pls-btn">Login to order</a>' . PHP_EOL;
        }

        echo '</div>' . PHP_EOL;
    }
}
