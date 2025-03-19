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
            echo '<div class="not-logged-in">';
            echo '<p>Please <a href="index.php?page=login">log in</a> to view your orders.</p>';
            echo '</div>';
            return;
        }
        
        // Check if we have order details
        if (empty($this->orderDetails)) {
            echo '<div class="no-order">';
            echo '<p>No order details available.</p>';
            echo '<p><a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a></p>';
            echo '</div>';
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

        echo '<div class="confirmation">';
        echo '<div class="confirmation-header">';
        echo '<h3>Thank you for your order!</h3>';
        echo '<p>Order #' . htmlspecialchars($order['order_id']) . ' placed on ' . date('F j, Y, g:i a', strtotime($order['order_date'])) . '</p>';
        echo '</div>';
        
        echo '<div class="order-summary">';
        echo '<h3>Order Summary</h3>';
        echo '<table class="order-items">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Product</th>';
        echo '<th>Name</th>';
        echo '<th>Price</th>';
        echo '<th>Quantity</th>';
        echo '<th>Total</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            
            $imagePath = "assets/images/" . htmlspecialchars($item['image']);
            if (!file_exists($imagePath)) {
                $imagePath = "assets/images/placeholder.png";
            }
            
            echo '<tr class="item">';
            echo '<td><img src="' . $imagePath . '" alt="' . htmlspecialchars($item['name']) . '"></td>';
            echo '<td>' . htmlspecialchars($item['name']) . '</td>';
            echo '<td>€' . number_format($item['price'], 2) . '</td>';
            echo '<td>' . $item['quantity'] . '</td>';
            echo '<td>€' . number_format($itemTotal, 2) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '<tfoot>';
        echo '<tr>';
        echo '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>';
        echo '<td>€' . number_format($totalAmount, 2) . '</td>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';
        echo '</div>';
        
        echo '<div class="confirmation-actions">';
        echo '<a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a>';
        echo '</div>';
        echo '</div>';
    }
}