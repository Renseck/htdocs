<?php

namespace model;

use database\crudOperations;

class orderModel
{
    private $orderCrud;
    private $orderItemCrud;
    // =============================================================================================
    public function __construct()
    {
        $this->orderCrud = new crudOperations("orders");
        $this->orderItemCrud = new crudOperations("order_items");
    }

    // =============================================================================================
    /**
     * Creates new entries in the order and order_items tables
     * @param int $userId The user ID
     * @param array $cartItems
     * @return array
     */
    public function createOrder($userId, $cartItems)
    {
        // Start a transaction - this is a safe method of committing to the DB and rolling back in
        // case something goes wrong.
        $this->orderCrud->customQuery("START TRANSACTION");

        try
        {
            $orderData = ["user_id" => $userId];

            // Insert the order and get the order ID
            $orderId = $this->orderCrud->create($orderData);

            if (!$orderId)
            {
                throw new \Exception("Failed to create order");
            }

            // Populate with each item in the cart
        foreach ($cartItems as $item) 
        {
            // Verify product exists before trying to insert
            $productId = $item["product_id"];
            $productCheck = $this->orderCrud->customQuery(
                "SELECT product_id FROM products WHERE product_id = :product_id",
                [":product_id" => $productId]
            )->fetch(\PDO::FETCH_ASSOC);
            
            if (!$productCheck) {
                throw new \Exception("Product with ID {$productId} does not exist");
            }

            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
            $params = [
                ":order_id" => $orderId,
                ":product_id" => $productId,
                ":quantity" => $item["quantity"]
            ];
            
            // Execute the query and get detailed error information
            $stmt = $this->orderItemCrud->customQuery($sql, $params);
            $result = $stmt->rowCount() > 0;

            if (!$result)
            {
                // Get more specific error information
                $errorInfo = $this->orderItemCrud->getLastErrorInfo();
                throw new \Exception("Failed to add product {$productId} to order: " . json_encode($errorInfo));
            }
        }

            // Commit the transaction
            $this->orderCrud->customQuery("COMMIT");

            return ["success" => true, "message" => "Order placed successfully!", "order_id" => $orderId];

        }
        catch (\Exception $e)
        {
            // Rollback the transaction in case of an error, preserving the db
            $this->orderCrud->customQuery("ROLLBACK");
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // =============================================================================================
    /**
     * Get order information by order ID
     * @param int $orderId The order ID
     * @return bool|array
     */
    public function getOrderById($orderId)
    {
        // Get basic order info 
        $order = $this->orderCrud->readOne(["order_id" => $orderId]);

        if (!$order)
        {
            return false;
        }

        // Get the items related to the order as well
        $sql = "SELECT oi.*, p.name, p.price, p.image
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                WHERE oi.order_id = :order_id";

        $stmt = $this->orderCrud->customQuery($sql, [":order_id" => $orderId]);
        $orderItems = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $totalAmount = 0;
        foreach($orderItems as $item)
        {
            $totalAmount += $item["price"] * $item["quantity"];
        }

        return [
            'order' => $order,
            'items' => $orderItems,
            'total_amount' => $totalAmount
        ];

    }

    // =============================================================================================
    /**
     * Get all orders from a user by ID
     * @param int $userId The user ID
     * @return array
     */
    public function getUserOrders($userId)
    {
        $orders = $this->orderCrud->read("*", ["user_id" => $userId], 'order_date DESC');

        if (empty($orders))
        {
            return [];
        }

        // Calculate the total amount of money spent
        foreach ($orders as $order)
        {
            $sql = "SELECT SUM(p.price * oi.quantity) as total
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = :order_id";
                    
            $stmt = $this->orderCrud->customQuery($sql, [':order_id' => $order['order_id']]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            $order['total_amount'] = $result['total'] ?? 0;
        }

        return $orders;
    }
    
    // =============================================================================================
}