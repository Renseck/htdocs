<?php
// DB connection ensured by running through index

//=========================================
// Shows the entire webshop, with all items
//=========================================
function showWebshop($conn, $isLoggedIn = false){
	echo '<h1>Webshop</h1>';
	
	// Get items from database
	$sql = "SELECT * FROM products";
	$result = mysqli_query($conn, $sql);
	
	// Open the div, and loop through all items in the products database.
	echo '<div class="webshop">';
	
	while ($row = mysqli_fetch_assoc($result)) {
		showItemInWebshop($row, $isLoggedIn);
		}
	
	
	echo '</div>';
}

//=========================================
// Shows a single item in the webshop grid, including a clickable image which then enables us to redirect to an item specific page
//=========================================
function showItemInWebshop($item, $isLoggedIn) {
	echo '<div class="product">';
	echo '	<a href="?page=product&id=' . $item["id"] . '">';	
	echo '	<img src="images/' . $item["image"] . '?v=' . filemtime('images/' . $item["image"]) . '">';
	echo '  <p>' . $item["name"] . '<p>';
	echo '  <p class="price">€' . number_format($item["price"], 2) . '<p>';
	echo '  </a>';
	if ($isLoggedIn) {
            echo '<form method="post" class="order-form">';
            echo '	<input type="hidden" name="product_id" value="' . $item['id'] . '">';
            echo '	<input type="number" name="quantity" value="1" min="1" max="20">';
            echo '	<button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>';
            echo '</form>';
        }
	echo '</div>';
}

//=========================================
// Shows a single item in its own page
//=========================================
function showItemSingle($item, $isLoggedIn) {
	echo '<div class="product_container">';

	// Image + description
	echo '	<div class="img_highlight">';
	echo '		<img src="images/' . $item["image"] . '?v=' . filemtime('images/' . $item["image"]) . '">';
	echo '  	<h2>' . $item["name"] . '</h2>';
	echo '  	<p>' . $item["description"] . '</p>';
	echo '	</div>';

	// Order card
	echo '	<div class="order_card">';
	echo '		<h3>Order now</h3>';
	echo '		<p class="price">€' . number_format($item["price"], 2) . '</p>';
	if ($isLoggedIn) {
		echo '<form method="post" class="order-form">';
		echo '	<input type="hidden" name="product_id" value="' . $item['id'] . '">';
		echo '	<input type="number" name="quantity" value="1" min="1" max="20">';
		echo '	<button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>';
		echo '</form>';
	}
	echo '	</div>';

	echo '</div>';
}