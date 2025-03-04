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
		echo '<li><a href="index.php?page=logout">LOGOUT [' . strtoupper($sessionData["user_name"]) . ']</a></li>';
		echo '<li><a href="index.php?page=cart">CART</a></li>';
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

function getItemFromProducts($conn, $product_id) {
	$sql = "SELECT * FROM products WHERE id=?";
	$stmt = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($stmt, "i", $product_id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$product = mysqli_fetch_assoc($result);
	return $product;
}

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

function updateCart($product_id, $quantity) {
	// Start by sanitizing input
	$product_id = intval($product_id);
	$quantity = intval($quantity);

	// Check if the item is in the cart to begin with
	if (isset($_SESSION["cart"][$product_id])) {
		$_SESSION["cart"][$product_id] = $quantity;
	}
}