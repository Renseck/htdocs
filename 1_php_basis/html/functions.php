<?php
echo '<link rel="stylesheet" type="text/css" href="../stylesheets/mystyle.css">';

//===================================
// Show page title
//===================================
function showTitle()
{
	echo '<title>My first website</title>';
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
		<li><a href="home.php">HOME</a></li>
		<li><a href="about.php">ABOUT</a></li>
		<li><a href="contact.php">CONTACT</a></li>
	</ul>';
} 

//===================================
// Show page footer with current year
//===================================
function showFooter()
{
	echo "<footer class='footer'>&copy;&nbsp;".date("Y")."&nbsp;Rens van Eck</footer>";
}