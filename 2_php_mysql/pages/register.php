<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function showRegister(){
	if (!empty($_SESSION["register_error"])){
		echo '<p class="error">' . $_SESSION["register_error"] . '</p>';
		unset($_SESSION["register_error"]);
	}
	
	echo '
		<h2>Register</h2>
		<div class="maintext">	
		<form action="auth/register_user.php" method="POST">
			<div class="input-group">
				<label for="name">Name:</label><br>
				<input type="text" id="name" name="name" required><br>
			</div>
			<div class="input-group">
				<label for="email">Email:</label><br>
				<input type="text" id="email" name="email" required><br>
			</div>
			<div class="input-group">
				<label for="password">Password:</label><br>
				<input type="password" id="password" name="password" required><br>
			</div>
			<div class="input-group">
				<label for="password_repeat">Password repeat:</label><br>
				<input type="password" id="password_repeat" name="password_repeat" required><br>
			</div>
			<input type="submit" value="Register">
		</form>
		</div>
	';
}