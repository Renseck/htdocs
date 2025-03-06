<?php

//======================================================================
// Show contact form. Retains pre-filled fields and shows eror messages.
//======================================================================
function showContactForm($formData = [], $errorData = []) {	
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