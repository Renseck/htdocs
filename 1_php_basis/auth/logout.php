<?php
// Logging out should be as easy as emptying the session and returning to the main page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();
header("Location: ../index.php");
exit();