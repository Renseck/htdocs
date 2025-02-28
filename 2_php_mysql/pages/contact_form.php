<?php

//======================================================================
// Processes the information of the contact form; cleans everything up and returns
// an array with a success boolean, errors and the data entered into the form
//======================================================================
function processContactForm($postData) {
	$errors = [];
	$formData = [];
	
	// Validate name
	if (empty($_POST["name"])) {
		$errors["name"] = "Name is required!";
	} else { 
		$formData["name"] = check_input($postData["name"]);
	}
	
	// Validate email
	if (empty($postData["email"])) { 
		$errors["email"] = "Email is required!";
	} else { 
		$cleanEmail = check_input($postData["email"]);
		if (!filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
			$errors["email"] = "Invalid email format!";
		} else {
			$formData["email"] = $cleanEmail;
		}
	}
	
	// Validate message
	if (empty($postData["message"])) { 
		$errors["message"] = "Message is required!";
	} else {
		$formData["message"] = check_input($postData["message"]);
	}
	
	// Return results for handling in index.php
	// If $errors is empty, success is true
	return [
		"success" => empty($errors),
		"errors" => $errors,
		"form_data" => $formData
	];
	
}
