<?php
session_start();
session_unset();
session_destroy();
header("Location: /1_php_basis/index.php");
exit(); // Ensure script stops execution
