<?php

//======================================================================
// Show contact form. Retains pre-filled fields and shows eror messages.
//======================================================================
function showContactForm($formData = [], $errorData = []) {
	// Retrieve the session values, to show pre-filled fields and error messages. This structure with ? : works like if-elseif-else statements
	// BONUS: pre-fill the username and email adress when opening the contact form when the user is logged in
	//$name = isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : (isset($_SESSION["form_data"]["name"]) ? $_SESSION["form_data"]["name"] : "");
	//$email = isset($_SESSION["user_email"]) ? $_SESSION["user_email"] : (isset($_SESSION["form_data"]["email"]) ? $_SESSION["form_data"]["email"] : "");
	//$message = isset($_SESSION["form_data"]["message"]) ? $_SESSION["form_data"]["message"] : "";
	//$nameErr = isset($_SESSION["form_data"]["nameErr"]) ? $_SESSION["form_data"]["nameErr"] : "";
	//$emailErr = isset($_SESSION["form_data"]["emailErr"]) ? $_SESSION["form_data"]["emailErr"] : "";
	//$messageErr = isset($_SESSION["form_data"]["messageErr"]) ? $_SESSION["form_data"]["messageErr"] : "";
	
	$name = $formData["name"] ?? "";
	$email = $formData["email"] ?? "";
	$message = $formData["message"] ?? "";
	$nameErr = $errorData["name"] ?? "";
	$emailErr = $errorData["email"] ?? "";
	$messageErr = $errorData["message"] ?? "";
	
	// Show the contact form
	echo '<div class="maintext">';
	echo '<form method="POST" action="index.php?page=contact">';
	echo '	<input type="hidden" name="contact" value="1">';
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
}

//======================================================================
// Show contact success. Retains pre-filled fields and shows eror messages.
//======================================================================

function showContactSuccess($contactData) {
	echo "<h2>Form submitted successfully!</h2>";
	echo "<p><strong>Name: </strong>" . htmlspecialchars($contactData["name"]) . "</p>";
	echo "<p><strong>Email address: </strong>" . htmlspecialchars($contactData["email"]) . "</p>";
	echo "<p><strong>Message: </strong>" . htmlspecialchars($contactData["message"]) . "</p>";
}