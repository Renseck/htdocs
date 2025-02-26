<?php
echo '<link rel="stylesheet" type="text/css" href="stylesheets/mystyle.css">';
include("pages/functions.php");

// Get page from GET, set to home if none is found
$page = $_GET["page"] ?? "home";

// Check if the requested page is allowed and boot out otherwise
$allowed_pages = ["home", "about", "contact"];
if (!in_array($page, $allowed_pages)){
	echo "<h1 style='font-size:100px; text-align:center; padding-top:20px;'>404 PAGE NOT FOUND</h1>";
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
}

showFooter();