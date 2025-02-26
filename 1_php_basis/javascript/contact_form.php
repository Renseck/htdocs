<?php
session_start();
$nameErr = $emailErr = $messageErr = "";
$name = $email = $message = "";
$hasError = false;
$_SESSION["form_data"] = [
			"name" => $name,
			"email" => $email,
			"message" => $message,
			"nameErr" => $nameErr,
			"emailErr" => $emailErr,
			"messageErr" => $messageErr
		];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["name"])) {
		$nameErr = "Name is required!";
		$hasError = true;
	} else { 
		$name = check_input($_POST["name"]);
	}
	
	if (empty($_POST["email"])) { 
		$emailErr = "Email is required!";
		$hasError = true;
	} else { 
		$email = check_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid email format!";
			$hasError = true;
		}
	}
	
	if (empty($_POST["message"])) { 
		$messageErr = "Message is required!";
		$hasError = true;
	} else {
		$message = check_input($_POST["message"]);
	}
	
	// If there is an error, refresh the session values
	if ($hasError) {
		$_SESSION["form_data"] = [
			"name" => $name,
			"email" => $email,
			"message" => $message,
			"nameErr" => $nameErr,
			"emailErr" => $emailErr,
			"messageErr" => $messageErr
		];
		
		//echo $emailErr;
		header("Location: ../html/contact.php");
		exit();
	}
	
	// Check if all fields have been filled
	if (!empty($name) && !empty($email) && !empty($message)) {
		echo "<h2>Form submitted successfully!</h2>";
		echo "<p><strong>Name: </strong>" . htmlspecialchars($name) . "</p>";
		echo "<p><strong>Email address: </strong>" . htmlspecialchars($email) . "</p>";
		echo "<p><strong>Message: </strong>" . htmlspecialchars($message) . "</p>";
	}
}

function check_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
}