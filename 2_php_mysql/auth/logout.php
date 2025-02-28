<?php
//===================================
// I wonder what this function does
//===================================
function logoutUser() {
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	
	session_unset();
	session_destroy();
	return true;
}