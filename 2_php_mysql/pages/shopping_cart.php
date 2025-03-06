<?php

//============================
// Show the contents of the cart session
//============================
function showShoppingCart($conn) {
    $cart = $_SESSION["cart"] ?? [];
    echo '<h1>Shopping cart</h1>';

    $total_price = 0;

    // Show cart contents. I want the user to be able to change the quantities of items in the cart as well
    // so i'll have to wrap a form around the table as well
    echo '<form method="post">';
    echo '  <table class="shopping_cart">';
    echo '      <tr><th></th><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th><th></th></tr>';

    // Loop through the items in the cart and generate an item card for each
    foreach ($cart as $id => $quantity) {
        // Get the item from the database
        $item = getItemFromProducts($conn, $id);

        $item_total = $item["price"] * $quantity;
        $total_price += $item_total;

        // Generate the item card. Maybe add an image into the card as well?
        echo '  <tr class="item">';
        echo '  <td><img src="images/' . $item["image"] . '"></td>';
        echo '  <td>' . $item["name"] . '</td>';
        echo '  <td>€' . number_format($item["price"], 2) . '</td>';

        // Quantity input. Start at the quantity found in shopping cart
        echo '  <td>';
        echo '  <input type="hidden" name="product_id[' . $item["product_id"] . ']" value="' .  $item["product_id"] . '">';
        echo '  <input type="number" name="quantity[' . $item["product_id"] . ']" value="' . $quantity . '" min="1" max="20">';
        echo '  </td>';

        echo '  <td>€' . number_format($item_total, 2) . '</td>';

        // Put the remove + update buttons under one table header
        echo '  <td style="gap:10px;>';
        // Remove button to delete an item from the cart
        echo '  <form method="post">';
        echo '      <input type="hidden" name="remove_id" value="' . $item["product_id"] . '">';
        echo '      <button type="submit" name="remove_from_cart" class="remove-btn">&times;</button>';
        echo '  </form>';

        // Update button. Maybe there's a way to update the quantities in the cart without having to press a button?
        echo '  <button type="submit" name="update_cart" class="update-btn">Update</button>';
        echo '  </td>';

        echo '  </tr>';
    }

    // Show the total price of all items in the cart
    echo '      <tr>';
    echo '      <td></td><td><strong>Total:</strong></td>'; 
    echo '      <td></td><td></td><td><strong>€' . number_format($total_price, 2) . '</strong></td>';
    echo '      </tr>';
    echo '      <tr><th>&#8203;</th><th></th><th></th><th></th><th></th><th></th></tr>';
    echo '  </table>';
    echo '</form>';
}

//============================
// Show the checkout button conditional on login status
//============================
function showCheckoutButton($isLoggedIn){
    echo '<div class="cart-checkout">';
    echo '  <form method="post">';
    echo '  <input type="hidden" name="page" value="checkout">';
    echo '  <button type="submit" name="checkout" class="checkout-btn" value="checkout">Checkout</button>';
    echo '  </form>';
    echo '</div>';
}