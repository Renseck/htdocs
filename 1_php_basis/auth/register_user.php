<?php
include("../pages/functions.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = check_input($_POST["name"]);
	$email = check_input($_POST["email"]);
	$password = check_input($_POST["password"]);
	$password_repeat = check_input($_POST["password_repeat"]);
	$file = "../users/users.txt";
	
	// Check if email is already registered. If yes, bounce back (or to login?)
	$users = file($file);
	foreach ($users as $user){
		list($stored_email) = explode('|', $user);
		if ($email == $stored_email) {
			$_SESSION["register_error"] = "Email is already registered!";
			header("Location: ../index.php?page=register");
			exit();
		}
	}
	
	// Check if passwords match. If not, bounce back
	if ($password !== $password_repeat) {
		$_SESSION["register_error"] = "Passwords don't match!";
		header("Location: ../index.php?page=register");
		exit();
	}
	
	// BONUS: Check if email is in a valid format
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$_SESSION["register_error"] = "Invalid email format!";
		header("Location: ../index.php?page=register");
		exit();
	}
	
	// Finally, nothing has gone wrong, so store the user info.
	file_put_contents($file, "$email|$name|$password\n", FILE_APPEND);
	
	$_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    header("Location: ../index.php");
    exit();
}