<?php
require("functions.php");

//===================================
// Show /Home/ main text
//===================================
function showMainText()
{
	echo '<div class="maintext">
		<p>
		Welcome to my first website, built on my first day of work at Educom.
		This serves as my first assignment. Lorem ipsum etc.
		</p>
		</div>
		<br>';
} 

showTitle();
showHeader();
showHyperlinkMenu();

showMainText();

showFooter();