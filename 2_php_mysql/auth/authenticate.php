<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to database of users
require "../pages/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = trim($_POST["email"]);
	$password = trim($_POST["password"]);
	
	$sql = "SELECT name, email, password FROM users WHERE email=?";
	$select_statement = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($select_statement, "s", $email);
	mysqli_stmt_execute($select_statement);
	
	// Get the result
	$result = mysqli_stmt_get_result($select_statement);
	
	// Check if we've gotten a result by seeing if the result has a length; if not, throw the unknown email error
	// If yes, check password validity and log in.
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$stored_email = $row["email"];
		$stored_name = $row["name"];
		$stored_password = $row["password"];
		if ($email == $stored_email && $password == $stored_password) {
			$_SESSION["user_name"] = $stored_name;
			$_SESSION["user_email"] = $stored_email;
			header("Location: ../index.php");
			exit();
		}
		
	} else {
		$_SESSION["login_error"] = "Invalid email or password.";
		header("Location: ../index.php?page=login");
		mysqli_close($conn);
		exit();
	}
}

