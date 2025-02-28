<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//======================================================================
// Show login form. 
//======================================================================
function showLogin(){
	echo '
		<h2>Login</h2>
		<div class="maintext">	
		<form action="index.php?page=login" method="POST">
			<input type="hidden" name="login" value="1">
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