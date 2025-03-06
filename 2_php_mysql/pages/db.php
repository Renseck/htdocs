<?php
//===================================
// Connects to the SQL database specified.
//===================================
function connectDatabase($dbhost, $dbname = "users", $dbuser, $dbpass) {
	// This can't be safe, can it?
	// Also, I realise this isn't a problem when this code is constantly run on  the same device, but the credentials will not be the same
	// and the database won't be present when I give this code to somebody else for feedback. Should I build in stuff to deal with that?

	// Connect to database
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// Check connection
	if ($conn->connect_error){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	return $conn;
}