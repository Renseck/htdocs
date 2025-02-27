<?php
include("../pages/functions.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to database of users
require "../pages/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = check_input($_POST["name"]);
	$email = check_input($_POST["email"]);
	$password = check_input($_POST["password"]);
	$password_repeat = check_input($_POST["password_repeat"]);
	
	// Take only name and email here, as we don't want to expose the passwords. This is not needed anyway, because we don't demand passwords to be unique.
	$sql = "SELECT name, email FROM users WHERE email=?";
	$select_statement = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($select_statement, "s", $email);
	mysqli_stmt_execute($select_statement);
	
	// Get the result
	$result = mysqli_stmt_get_result($select_statement);

	
	// Check if email is already registered by seeing if the result has length > 0. If yes, bounce back (or to login?)
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["register_error"] = "Email is already registered!";
		header("Location: ../index.php?page=register");
		exit();
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
	
	// Finally, nothing has gone wrong, so store the user info. Is this method safe against injection?
	// 		$new_user_sql = "INSERT INTO users (name, email, password) VALUES ('$name','$email','$password')";
	//		mysqli_query($conn, $new_user_sql);
	// No indeed! We should switch to prepared statements (w3schools):
	// I'll take the liberty of improving every other SQL query to this sort of statement as well.
	$new_user_sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
	$insert_statement = mysqli_prepare($conn, $new_user_sql);
	
	// "sss" for the three incoming (s)trings
	mysqli_stmt_bind_param($insert_statement, "sss", $name, $email, $password);
	
	// Execute
	mysqli_stmt_execute($insert_statement);
	
	// User is automatically logged in like this
	$_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    header("Location: ../index.php");
	
	// Close the statement and connection to the database
	mysqli_stmt_close($insert_statement);
	mysqli_close($conn);
	
    exit();
}

