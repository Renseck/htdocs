<?php
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
		<li><a href="index.php?page=contact">CONTACT</a></li>
	</ul>';
} 

//===================================
// Show page footer with current year
//===================================
function showFooter()
{
	echo "<footer class='footer'>&copy;&nbsp;".date("Y")."&nbsp;Rens van Eck</footer>";
}