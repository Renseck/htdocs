<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//======================================================================
// Show registration form.
//======================================================================
function showRegister(){
	echo '
		<h2>Register</h2>
		<div class="maintext">	
		<form action="index.php?page=register" method="POST">
			<input type="hidden" name="register" value="1">
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