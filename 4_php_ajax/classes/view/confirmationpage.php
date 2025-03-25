<?php

namespace view;

require_once 'classes/view/htmldocument.php';

use controller\sessionController;
use model\orderModel;

class confirmationPage extends \view\htmlDoc
{
    private $orderModel;
    private $orderDetails;

    // =============================================================================================
    public function __construct($pages)
    {
        parent::__construct("Order confirmation", $pages);
        $this->setPageHeaderText("Order confirmation");

        $this->orderModel = new orderModel();

        if (isset($_SESSION["last_order_id"]))
        {
            $this->orderDetails = $this->orderModel->getOrderById($_SESSION["last_order_id"]);
            unset($_SESSION["last_order_id"]);
        }
    }
    
    // =============================================================================================
    public function bodyContent()
    {
        parent::bodyContent();

        // Check if user is logged in
        if (!sessionController::isLoggedIn()) {
            echo '<div class="not-logged-in">' . PHP_EOL
                .'<p>Please <a href="index.php?page=login">log in</a> to view your orders.</p>' . PHP_EOL
                .'</div>' . PHP_EOL;
            return;
        }
        
        // Check if we have order details
        if (empty($this->orderDetails)) {
            echo '<div class="no-order">' . PHP_EOL
                .'<p>No order details available.</p>' . PHP_EOL
                .'<p><a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a></p>' . PHP_EOL
                .'</div>' . PHP_EOL;
            return;
        }
        
        $this->displayOrderConfirmation();
    }

    // =============================================================================================
    private function displayOrderConfirmation()
    {
        $order = $this->orderDetails["order"];
        $items = $this->orderDetails["items"];
        $totalAmount = $this->orderDetails["total_amount"];

        echo '<div class="confirmation">' . PHP_EOL
            .'<div class="confirmation-header">' . PHP_EOL
            .'<h3>Thank you for your order!</h3>' . PHP_EOL
            .'<p>Order #' . htmlspecialchars($order['order_id']) .
             ' placed on ' . date('F j, Y, g:i a', strtotime($order['order_date'])) . '</p>' . PHP_EOL
            .'</div>' . PHP_EOL
        
            .'<div class="order-summary">' . PHP_EOL
            .'<h3>Order Summary</h3>' . PHP_EOL
            .'<table class="order-items">' . PHP_EOL
            .'<thead>' . PHP_EOL
            .'<tr>' . PHP_EOL
            .'<th>Product</th>'
            .'<th>Name</th>'
            .'<th>Price</th>'
            .'<th>Quantity</th>'
            .'<th>Total</th>'
            .'</tr>' . PHP_EOL
            .'</thead>' . PHP_EOL
            .'<tbody>' . PHP_EOL;
        
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            
            $imagePath = "assets/images/" . htmlspecialchars($item['image']);
            if (!file_exists($imagePath)) {
                $imagePath = "assets/images/placeholder.png";
            }
            
            echo '<tr class="item">' . PHP_EOL
                .'<td><img src="' . $imagePath . '" alt="' . htmlspecialchars($item['name']) . '"></td>'. PHP_EOL
                .'<td>' . htmlspecialchars($item['name']) . '</td>'. PHP_EOL
                .'<td>€' . number_format($item['price'], 2) . '</td>'. PHP_EOL
                .'<td>' . $item['quantity'] . '</td>'. PHP_EOL
                .'<td>€' . number_format($itemTotal, 2) . '</td>'. PHP_EOL
                .'</tr>'. PHP_EOL;
        }
        
        echo '</tbody>'. PHP_EOL
            .'<tfoot>'. PHP_EOL
            .'<tr>'. PHP_EOL
            .'<td colspan="3" style="text-align: right. PHP_EOL"><strong>Total:</strong></td>'. PHP_EOL
            .'<td>€' . number_format($totalAmount, 2) . '</td>'. PHP_EOL
            .'</tr>'. PHP_EOL
            .'</tfoot>'. PHP_EOL
            .'</table>'. PHP_EOL
            .'</div>'. PHP_EOL
        
            .'<div class="confirmation-actions">'. PHP_EOL
            .'<a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a>'. PHP_EOL
            .'</div>'. PHP_EOL
            .'</div>'. PHP_EOL;
    }
}