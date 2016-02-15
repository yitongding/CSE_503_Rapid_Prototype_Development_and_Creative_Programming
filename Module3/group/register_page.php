<!DOCTYPE html>
<!-- This is the page for register  -->
<html>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>Register</title>
</head>

<body>
	<p><strong>Please enter your username and password</strong></p>
	<form action="./register_redir.php" method="POST">
		<p>
			<label for="usernameinput" >New Username:</label>
			<input type="text" name="username" id="usernameinput">
		</p>
		<p>
			<label for="passwordinput" >New Password:</label>
			<input type="password" name="password" id="passwordinput">
		</p>
		<p> <input type="submit" value="Register"> </p>
	</form>
	
	<!-- check if there is error -->
	<p>
		<?php
			session_start();
			if( isset($_SESSION['register_error']))
			{
				if ($_SESSION['register_error'] == 1)
				{
					printf("Username used.");
                    session_destroy();
				}
				if ($_SESSION['register_error'] == 2)
				{
					printf("database error.");
                    session_destroy();
				}
			}
            
			
		?>
	</p>
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
</html>