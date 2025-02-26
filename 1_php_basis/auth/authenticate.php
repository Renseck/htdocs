<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = trim($_POST["email"]);
	$password = trim($_POST["password"]);
	$file = "../users/users.txt";
	
	// In case the file with login info is missing
	if (!file_exists($file)){
		$_SESSION['login_error'] = "No users found."; 
        header("Location: ../index.php?page=login");
        exit();
	}
	
	// Loop through the file of users and see if the login values match any (wildly inefficient but im sure we'll improve it later
	// Also unsafe without encoding anything but you know
	$users = file($file, FILE_IGNORE_NEW_LINES);
	foreach ($users as $user){
		list($stored_email, $stored_name, $stored_password) = explode('|', $user);
		if ($email == $stored_email && $password == $stored_password) {
			$_SESSION["user_name"] = $stored_name;
			$_SESSION["user_email"] = $stored_email;
			header("Location: ../index.php");
			exit();
		}
	} 
	$_SESSION["login_error"] = "Invalid email or password.";
    header("Location: ../index.php?page=login");
    exit();
}