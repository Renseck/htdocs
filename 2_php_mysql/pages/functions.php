<?php
//===================================
// Show page title
//===================================
function showTitle($title)
{
	echo '<title>'.$title.'</title>';
} 

//===================================
// Show page header
//===================================
function showHeader()
{
	echo '<h1>Hello World!</h1>';
} 

//===================================
// Show hyperlinks at the top of the page
//===================================
function showHyperlinkMenu($sessionData = [])
{
	echo '<ul class="menu">
		<li><a href="index.php?page=home">HOME</a></li>
		<li><a href="index.php?page=about">ABOUT</a></li>
		<li><a href="index.php?page=contact">CONTACT</a></li>
		<li><a href="index.php?page=webshop">WEBSHOP</a></li>';
		
	// If there is a username, show logout instead of login+register
	if (isset($sessionData["user_name"])) {
		$num_items = isset($sessionData["cart"]) ? count($sessionData["cart"]) : 0;
		echo '<li><a href="index.php?page=logout">LOGOUT [' . strtoupper($sessionData["user_name"]) . ']</a></li>';
		echo '<li><a href="index.php?page=cart">CART [' . $num_items . ']</a></li>';
	} else {
		echo '<li><a href="index.php?page=login">LOGIN</a></li>';
		echo '<li><a href="index.php?page=register">REGISTER</a></li>';
	}
	
	echo '</ul>';
} 

//===================================
// Show page footer with current year
//===================================
function showFooter()
{
	echo '<br>';
	echo "<footer class='footer'>&copy;&nbsp;".date("Y")."&nbsp;Rens van Eck</footer>";
}

//===================================
// Show placeholder page
//===================================
function showPlaceholder(){
	echo '<h1 style="color:red">BIG PLACEHOLDER</h1>';
}

//============================
// Check data for special chars
//============================
function check_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
}

//===================================
// Show the general login to order button
//===================================
function showLoginPleaseBtn(){
	echo '<form method="post" action="index.php?page=login">';
	echo '	<button type="submit" class="login-pls-btn">Login to order</button>';
	echo '</form>';
}

//============================
// Get product/item information from the `products` table
//============================
function getItemFromProducts($conn, $product_id) {
	$sql = "SELECT * FROM products WHERE product_id=?";
	$stmt = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($stmt, "i", $product_id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$product = mysqli_fetch_assoc($result);
	mysqli_stmt_close($stmt);
	return $product;
}

//============================
// Add a certain amount of a product to the cart session
//============================
function addToCart($product_id, $quantity) {
	// Start by sanitizing input
	$product_id = intval($product_id);
	$quantity = intval($quantity);

	// Ensure the cart actually exists
	if (!isset($_SESSION["cart"])) {
		$_SESSION["cart"] = [];
	}

	// To be safe: If the quantity at least 1, get the product info from the database to see if it actually exists
	if ($quantity > 0) {
		// If the product is already in the cart, only increment the quantity (UP TO A MAXIMUM OF 20)
		// Else add the item newly into the cart
		if (isset($_SESSION["cart"][$product_id])) {
			$_SESSION["cart"][$product_id] += $quantity;
			if ($_SESSION["cart"][$product_id] > 20) {
				$_SESSION["cart"][$product_id] = 20;
			}
		} else {
			$_SESSION["cart"][$product_id] = min($quantity, 20);
		}
	}
}

//============================
// Change an item's quantity in the cart session to a set amount
//============================
function updateCart($product_id, $quantity) {
	// Start by sanitizing input
	$product_id = intval($product_id);
	$quantity = intval($quantity);

	// Check if the item is in the cart to begin with
	if (isset($_SESSION["cart"][$product_id])) {
		$_SESSION["cart"][$product_id] = $quantity;
	}
}

//============================
// Remove an item from the cart session
//============================
function removeItemfromCart($product_id) {
	// To be safe: check that the item is indeed in the cart. If yes, unset that part of the cart
	if (isset($_SESSION["cart"][$product_id])) {
		unset($_SESSION["cart"][$product_id]);
	}
}

//============================
// Process the cart's contents into the database
//============================
function processCheckout($conn){
	// Write stuff to the two tables in the database and clear the session
	$order_id = writeToOrders($conn);
	writeToOrderItems($conn, $order_id);
	unset($_SESSION["cart"]);
}

//============================
// Process the cart's contents into the `orders` table
//============================
function writeToOrders($conn) {
	$user_id = $_SESSION["user_id"] ?? null;

	$sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
	$stmt = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($stmt, "i", $user_id);
	mysqli_stmt_execute($stmt);
	$order_id = mysqli_insert_id($conn);
	mysqli_stmt_close($stmt);

	return $order_id;

}

//============================
// Process the cart's contents into the `order_items` table
//============================
function writeToOrderItems($conn, $order_id) {
	$sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
	$stmt = mysqli_prepare($conn, $sql);

	foreach ($_SESSION["cart"] as $product_id => $quantity) {
		mysqli_stmt_bind_param($stmt, "iii", $order_id, $product_id, $quantity);
		mysqli_stmt_execute($stmt);
	}
	
	mysqli_stmt_close($stmt);
}