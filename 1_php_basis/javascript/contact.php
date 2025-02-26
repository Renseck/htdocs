<?php
$nameErr = $emailErr = $messageErr = "";
$name = $email = $message = "";
$hasError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["name"])) {
		$nameErr = "Name is required";
		$hasError = true;
	} else { 
		$name = check_input($_POST["name"]);
	}
	
	if (empty($_POST["email"])) { 
		$emailErr = "Email is required";
		$hasError = true;
	} else { 
		$email = check_input($_POST["email"]);
	}
	
	if (empty($_POST["message"])) { 
		$messageErr = "Message is required";
		$hasError = true;
	} else {
		$message = check_input($_POST["message"]);
	}
	
	if ($hasError) {
		$params = http_build_query([
			"nameErr" => $nameErr,
			"emailErr" => $emailErr,
			"messageErr" => $messageErr,
			"name" => $name,
			"email" => $email,
			"message" => $message
		]);
		header("Location: ../html/contact.html?$params");
		exit();
	}
	
	// Check if all fields have been filled
	if (!empty($name) && !empty($email) && !empty($message)) {
		echo "<h2>Form submitted successfully!</h2>";
		echo "<p>Name: " . htmlspecialchars($name) . "</p>";
		echo "<p>Email address: " . htmlspecialchars($email) . "</p>";
		echo "<p>Message: " . htmlspecialchars($message) . "</p>";
	}
}

function check_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>