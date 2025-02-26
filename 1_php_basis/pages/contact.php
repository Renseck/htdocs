<?php
session_start();

//======================================================================
// Show contact form. Retains pre-filled fields and shows eror messages.
//======================================================================
function showContactForm()
{
	// Retrieve the session values, to show pre-filled fields and error messages
	$name = isset($_SESSION["form_data"]["name"]) ? $_SESSION["form_data"]["name"] : "";
	$email = isset($_SESSION["form_data"]["email"]) ? $_SESSION["form_data"]["email"] : "";
	$message = isset($_SESSION["form_data"]["message"]) ? $_SESSION["form_data"]["message"] : "";
	$nameErr = isset($_SESSION["form_data"]["nameErr"]) ? $_SESSION["form_data"]["nameErr"] : "";
	$emailErr = isset($_SESSION["form_data"]["emailErr"]) ? $_SESSION["form_data"]["emailErr"] : "";
	$messageErr = isset($_SESSION["form_data"]["messageErr"]) ? $_SESSION["form_data"]["messageErr"] : "";
	
	// Show the contact form
	echo '<div class="maintext">';
	echo '<form method="POST" action="javascript/contact_form.php">';
	echo '	<div class="input-group">';
	echo '		<label for="name">Name:</label><br>';
	echo '		<input type="text" id="name" name="name" value="' . htmlspecialchars($name) . '"><br>';
	
	if (!empty($nameErr)) {
		echo ' <span id="nameErr" class = "error">'.htmlspecialchars($nameErr).'</span>';
	}
	
	echo '	</div>';
	echo '	<div class="input-group">';
	echo '		<label for="email">Email address:</label><br>';
	echo '		<input type="text" id="email" name="email" value="' . htmlspecialchars($email) . '"><br>';
	
	if (!empty($emailErr)) { 
		echo '		<span id="emailErr" class = "error">'.htmlspecialchars($emailErr).'</span>';
	}
	
	echo '	</div>';
	echo '	<div class="input-group">';
	echo '		<label for="message">Message:</label><br>';
	echo '		<input type="text" id="message" name="message" value="' . htmlspecialchars($message) . '"><br>';
	
	if (!empty($messageErr)) { 
		echo '		<span id="messageErr" class = "error">'.htmlspecialchars($messageErr).'</span>';
	}
	
	echo '	</div>';
	echo '	<input type="submit" value="Send">';
	echo '</form>';
	echo '</div>';
	echo '<br>';
	session_unset();
	session_destroy();
}