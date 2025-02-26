<?php
echo '<link rel="stylesheet" type="text/css" href="stylesheets/mystyle.css">';
include("pages/functions.php");

// Get page from GET, set to home if none is found
$page = $_GET["page"] ?? "home";

// Check if the requested page is allowed and boot out otherwise
$allowed_pages = ["home", "about", "contact", "login", "register", "logout"];
if (!in_array($page, $allowed_pages)){
	$page = "404";
}

showHeader();
showHyperlinkMenu();

switch($page){
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
		showContactForm();
		break;
		
	case "login":
		include "pages/login.php";
		showTitle("Login - My first website");
		showLogin();
		break;
		
	case "register":
		include "pages/register.php";
		showTitle("Register - My first website");
		showRegister();
		break;
		
	case "logout":
		include "auth/logout.php";
		break;
		
	case "404":
		include  "pages/404.php";
		break;
}

showFooter();