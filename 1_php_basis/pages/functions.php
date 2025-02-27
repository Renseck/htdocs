<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo '<link rel="stylesheet" type="text/css" href="../stylesheets/mystyle.css">';

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
function showHyperlinkMenu()
{
	echo '<ul class="menu">
		<li><a href="index.php?page=home">HOME</a></li>
		<li><a href="index.php?page=about">ABOUT</a></li>
		<li><a href="index.php?page=contact">CONTACT</a></li>';
		
	// If there is a username, show logout instead of login+register
	if (isset($_SESSION["user_name"])) {
		echo '<li><a href="index.php?page=logout">LOGOUT ['.$_SESSION["user_name"].']</a></li>';
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

//============================
// Check data for special chars
//============================
function check_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
}