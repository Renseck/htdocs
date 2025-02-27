<?php
$servername = "localhost";
$username = "root";
$password = "Jz37!hs8dbEx6Erm";
$dbname = "users";

// Connect to database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error){
	die("Connection failed: " . mysqli_connect_error());
}