<?php
session_start();
session_unset();
session_destroy();
header("Location: /2_php_mysql/index.php");
exit(); // Ensure script stops execution
