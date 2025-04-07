<?php
// This file returns only the content portion for AJAX requests

$page = $_GET["page"] ?? "home";

switch($page)
{
    case "home":
        include "_src/view/home.php";
        $homepage = new homePage();
        echo $homepage->mainContent();
        break;
        
    case "about":
        include "_src/view/about.php";
        $aboutpage = new aboutPage();
        echo $aboutpage->mainContent();
        break;
    
    default:
        echo "<p>Page not found</p>";
}