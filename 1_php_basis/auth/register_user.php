<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = trim($_POST["name"]);
	$email = trim($_POST["email"]);
	$password = trim($_POST["password"]);
	$password_repeat = trim($_POST["password_repeat"]);
	$file = "../users/users.txt";
	
	// Check if passwords match
	if ($password !== $password_repeat) {
		$_SESSION["register_error"] = "Passwords don't match!";
		header("Location: ../index.php?page=register");
		exit();
	}
	
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
	
	// Finally, nothing has gone wrong, so store the user info.
	file_put_contents($file, "$email|$name|$password\n", FILE_APPEND);
	
	$_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    header("Location: ../index.php");
    exit();
}