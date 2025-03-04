<?php
//======================================================================
// Processes the information of the registration form; cleans everything up and returns
// an array with a success boolean, errors and the username + email entered into the form
//======================================================================
function processRegistration($conn, $postData) {
	$name = check_input($postData["name"]);
	$email = check_input($postData["email"]);
	$password = check_input($postData["password"]);
	$password_repeat = check_input($postData["password_repeat"]);
	
	// Check if passwords match. If not, bounce back
	if ($password !== $password_repeat) {
		return [
			"success" => false,
			"error" => "Passwords don't match!"
		];
	}
	
	// BONUS: Check if email is in a valid format
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return [
			"success" => false,
			"error" => "Invalid email format!"
		];
	}
	
	// Take only name and email here, as we don't want to expose the passwords. This is not needed anyway, because we don't demand passwords to be unique.
	$sql = "SELECT name, email FROM users WHERE email=?";
	$select_statement = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($select_statement, "s", $email);
	mysqli_stmt_execute($select_statement);
	
	// Get the result
	$result = mysqli_stmt_get_result($select_statement);

	
	// Check if email is already registered by seeing if the result has length > 0. If yes, return success false and an error message
	if (mysqli_num_rows($result) > 0) {
		return [
			"success" => false,
			"error" => "Email is already registered!"
		];
	}
	mysqli_stmt_close($select_statement);

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
	
	// Check if the SQL command went through correctly, mostly to display the "registered successfully" message later
	if (mysqli_stmt_affected_rows($insert_statement) > 0) {
		return [
			"success" => true,
			"user_name" => $name,
			"user_email" => $email
		];
	} else {
		// Not sure what to actually do if this ever occurs; let's pray it never does
		return [
			"success" => false,
			"error" => "Registration failed. Please try again."
		];
	}
	
	// Close the statement and connection to the database
	mysqli_stmt_close($insert_statement);
	mysqli_close();
}

