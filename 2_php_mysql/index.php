<?php
echo '<!DOCTYPE html>';
echo '<link rel="stylesheet" type="text/css" href="stylesheets/mystyle.css">';
require_once "pages/functions.php";
require_once 'pages/db.php'; // Make sure $conn is available
require_once __DIR__ . '/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// I'll use comments to outline which steps the index.php code needs to take, in accordance with feedback given by Geert

// 1. Determine what is being requested via $_GET
// Get page from GET, set to home if none is found
$page = $_GET["page"] ?? "home";
$id = $_GET["id"] ?? 2;

// 2. Process any POST requests (so login, registration and the contact form)
// Here, data will be written to $_SESSION so as to be usable in the different scope of step 3
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$userCreds = getUsersCreds();
	$conn = connectDatabase($userCreds["db_host"], $userCreds["db_name"], $userCreds["db_user"], $userCreds["db_pass"]);
	// Handle login
	if (isset($_POST["login"])) {
		require_once "auth/authenticate.php";
		$loginResult = processLogin($conn, $_POST);
		if ($loginResult["success"]) {
			$_SESSION["user_name"] = $loginResult["user_name"];
			$_SESSION["user_email"] = $loginResult["user_email"];
			$_SESSION["user_id"] = $loginResult["user_id"];
		} else {
			$_SESSION["login_error"] = $loginResult["error"];
		}
	}
	
	// Handle registration
	if (isset($_POST["register"])) {
		require_once "auth/register_user.php";
		$registerResult = processRegistration($conn, $_POST);
		if (!$registerResult["success"]) {
			$_SESSION["register_error"] = $registerResult["error"];
		}
	}
	
	// Handle contact
	if (isset($_POST["contact"])) {
		require_once "pages/contact_form.php";
		$contactResult = processContactForm($_POST);
		if ($contactResult["success"]) {
			$_SESSION["contact_success"] = "Form submitted successfully!";
			$_SESSION["form_data"] = $contactResult["form_data"];
		}
		else {
			$_SESSION["contact_error"] = $contactResult["errors"];
			$_SESSION["form_data"] = $contactResult["form_data"];
		}
	}

	// Handle adding items to cart
	if (isset($_POST["add_to_cart"])) {
		$product_id = $_POST["product_id"];
		$quantity = $_POST["quantity"];

		// Check if the product actually exists
		$product = getItemFromProducts($conn, $product_id);
		if ($product){
			addToCart($product_id, $quantity);
		}
	}

	// Handle updating item quantities from within the cart
	if (isset($_POST["update_cart"])) {
		$product_id = $_POST["product_id"];
		$quantity = $_POST["quantity"];

		foreach ($quantity as $id => $qty){
			updateCart($id, $qty);
		}
		
	}

	// Handle removing items from the cart completely 
	if (isset($_POST["remove_from_cart"])) {
		$product_id = $_POST["remove_id"];
		removeItemfromCart($product_id);
	}

	// Handle checkouts
	if (isset($_POST["checkout"])) {
		if (isset($_SESSION["user_name"])) {
			// Write stuff to the database
			processCheckout($conn);
			// Facilitate the switch to the checkout page
			$page = $_POST["page"];
		}
		
	}
}


// 3. Display the result
// Check if the requested page is allowed and boot out otherwise
$allowed_pages = ["home", "about", "contact", "webshop", "login", "register", "logout", "product", "cart", "checkout"];
if (!in_array($page, $allowed_pages)){
	$page = "404";
}

showHeader();
showHyperlinkMenu($_SESSION);

// This could be consolidated by doing a sort of include pages/$page type thing, and then having all functions be run directly in their respective files. Easier to expand the webpage, but also more uncontrolled.
switch($page)
{
	case "home":
		include "pages/home.php";
		showTitle("Home - My first website");
		showHomeMainText();
		break;
		
	case "about":
		include "pages/about.php";
		showTitle("About - My first website");
		showSecondaryHeader();
		showAboutMainText();
		break;
		
	case "contact":
		include "pages/contact.php";
		showTitle("Contact - My first website");
		
		// Code here to pre-fill fields if the user is logged in?
		$contactData = [
			"name" => isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : "",
			"email" => isset($_SESSION["user_email"]) ? $_SESSION["user_email"] : "",
			"message" => ""
		];

		$errorData = [];
		
		// If there was an error with filling the contact form, set the contact and error data (override as needed)
		if (!empty($_SESSION["contact_error"]))
		{
			$contactData = $_SESSION["form_data"];
			$errorData = $_SESSION["contact_error"];
			unset($_SESSION["contact_error"]);
		}
		
		showContactForm($contactData, $errorData);
		break;
		
	case "webshop":
		include "pages/webshop.php";
		showTitle("Webshop - My first website");
		
		$userCreds = getUsersCreds();
		$conn = connectDatabase($userCreds["db_host"], $userCreds["db_name"], $userCreds["db_user"], $userCreds["db_pass"]);
		$isLoggedIn = isset($_SESSION["user_name"]) ? : false;
		
		showWebshop($conn, $isLoggedIn);
		break;
		
	case "product":
		require_once "pages/webshop.php";
		
		$userCreds = getUsersCreds();
		$conn = connectDatabase($userCreds["db_host"], $userCreds["db_name"], $userCreds["db_user"], $userCreds["db_pass"]);
		$isLoggedIn = isset($_SESSION["user_name"]) ? : false;
		
		$item = getItemFromProducts($conn, $id);
		
		if ($item){
			showTitle($item["name"] . " - My first website");
			showItemSingle($item, $isLoggedIn);
		} else {
			echo "<h1>Product not found</h1>";
		}
		
		
		break;
		
	case "cart":
		include "pages/shopping_cart.php";
		showTitle("Shopping cart - My first website");

		$userCreds = getUsersCreds();
		$conn = connectDatabase($userCreds["db_host"], $userCreds["db_name"], $userCreds["db_user"], $userCreds["db_pass"]);
		$isLoggedIn = isset($_SESSION["user_name"]) ? : false;
		$cart = $_SESSION["cart"] ?? [];

		// If user is not logged in, tell em to log in or get lost
		if (!$isLoggedIn) {
			echo '<h3 style="text-align: center;">You have to be logged in to put items in your cart &#128533;.</h3>';
			echo '<div style="display: flex; align-items: center; justify-content: center;">';
			showLoginPleaseBtn();
			echo '</div>';
		}
		
		if ($isLoggedIn) {
			showShoppingCart($conn);
			if (!empty($cart)) {
				showCheckoutButton($isLoggedIn);
			}
		}

		break;
	
	case "checkout":
		include "pages/checkout.php";
		showTitle("Checkout - My first website");
		showCheckoutPage();
		break;

	case "login":
		include "pages/login.php";
		showTitle("Login - My first website");
		
		if (!empty($_SESSION["login_error"]))
		{
			echo '<p class="error">' . $_SESSION["login_error"] . '</p>';
			unset($_SESSION["login_error"]);
		}
		
		showLogin();
		break;
		
	case "register":
		include "pages/register.php";
		showTitle("Register - My first website");
		
		if (!empty($_SESSION["register_error"]))
		{
			echo '<p class="error">' . $_SESSION["register_error"] . '</p>';
			unset($_SESSION["register_error"]);
		}
		
		showRegister();
		break;
		
	case "logout":
		include "auth/logout.php";
		logoutUser();
		// return the user to where they were
		$previousPage = $_SERVER["HTTP_REFERER"] ?? "index.php";
		header("Location: " . $previousPage);
		break;
		
	case "404":
		include  "pages/404.php";
		showTitle("Page not found - My first website");
		showPage404();
		break;
		
}

showFooter();