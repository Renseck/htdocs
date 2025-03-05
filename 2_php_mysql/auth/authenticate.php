<?php
//===================================
// Processes a login request by checking the given email and password against the stored values in the database.
// Returns success true and user data when the login info is correct, otherwise success false and an error
//===================================
function processLogin($conn, $postData) {
	
	$email = trim($postData["email"]);
	$password = trim($postData["password"]);
	
	$sql = "SELECT * FROM users WHERE email=?";
	$select_statement = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($select_statement, "s", $email);
	mysqli_stmt_execute($select_statement);
	
	// Get the result
	$result = mysqli_stmt_get_result($select_statement);
	
	// Check if we've gotten a result by seeing if the result has a length; if not, throw the unknown email error
	// If yes, check password validity. Return success messages and user info as needed
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$stored_email = $row["email"];
		$stored_name = $row["name"];
		$stored_password = $row["password"];
		$stored_id = $row["id"];
		
		if ($email == $stored_email && $password == $stored_password) {
			return [
				"success" => true,
				"user_name" => $stored_name,
				"user_email" => $stored_email,
				"user_id" => $stored_id
			];
		} else {
			return [
				"success" => false,
				"error" => "Invalid email or password"
			];
		}
		
	} else {
		return [
				"success" => false,
				"error" => "Invalid email or password"
			];
	}
	
	mysqli_stmt_close($select_statement);
	mysqli_close();

}