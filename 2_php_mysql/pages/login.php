<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function showLogin(){
	if (!empty($_SESSION["login_error"])){
		echo '<p class="error">' . $_SESSION["login_error"] . '</p>';
		unset($_SESSION["login_error"]);
	}

	echo '
		<h2>Login</h2>
		<div class="maintext">	
		<form action="auth/authenticate.php" method="POST">
			<div class="input-group">
				<label for="email">Email:</label><br>
				<input type="text" id="email" name="email" required><br>
			</div>
			<div class="input-group">
				<label for="password">Password:</label><br>
				<input type="password" id="password" name="password" required><br>
			</div>
			<input type="submit" value="Login">
		</form>
		</div>
	';
}