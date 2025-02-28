<?php
//===================================
// Connects to the SQL database specified.
//===================================
function connectDatabase($dbname = "users") {
	// This can't be safe, can it?
	$servername = "localhost";
	$username = "root";
	$password = "Jz37!hs8dbEx6Erm";

	// Connect to database
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	return $conn;
}