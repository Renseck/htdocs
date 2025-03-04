<?php

function showShoppingCart($conn, $sessionData) {
    $cart = $sessionData["cart"] ?? [];
    echo '<h1>Shopping Cart</h1>';

    // If cart is empty, show message
    if (empty($cart)) {
        echo '<p>Your cart is empty :(.</p>';
        echo '<a href="?page=webshop" class="button">Continue Shopping</a>';
        return;
    }

    $total_price = 0;

    // Show cart contents. I want the user to be able to change the quantities of items in the cart as well
    // so i'll have to wrap a form around the table as well
    echo '<form method="post">';
    echo '<table class="shopping_cart">';
    echo '<tr><th>Image</th><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>';

    // Loop through the items in the cart and generate an item card for each
    foreach ($cart as $id => $quantity) {
        // Get the item from the database
        $sql = "SELECT * FROM products WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);    
        $item = mysqli_fetch_assoc($result);

        $item_total = $item["price"] * $quantity;
        $total_price += $item_total;

        // Generate the item card. Maybe add an image into the card as well?
        echo '<tr>';
        echo '<td><img src="images/' . $item["image"] . '"></td>';
        echo '<td>' . $item["name"] . '</td>';
        echo '<td>€' . number_format($item["price"], 2) . '</td>';

        // Quantity input. Start at the quantity found in shopping cart
        echo '<td>';
        echo '<input type="hidden" name="product_id" value="' .  $item["id"] . '">';
        echo '<input type="number" name="quantity" value="' . $quantity . '" min="1" max="20">';
        echo '</td>';

        echo '<td>€' . number_format($item_total, 2) . '</td>';

        // Update button. Maybe there's a way to update the quantities in the cart without having to press a button?
        echo '<td><button type="submit" name="update_cart">Update</button></td>';

        echo '</tr>';
    }
    mysqli_stmt_close($stmt);

    // Show the total price of all items in the cart
    echo '<tr>';
    echo '<td></td><td><strong>Total:</strong></td>'; 
    echo '<td></td><td></td><td><strong>€' . number_format($total_price, 2) . '</strong></td>';
    echo '</tr>';
    echo '</table>';
    echo '</form>';

    echo '<div class="cart-checkout">';
    echo '  <form method="post"><button type="submit" name="checkout" class="checkout-btn">Checkout</button></form>';
    echo '</div>';
}