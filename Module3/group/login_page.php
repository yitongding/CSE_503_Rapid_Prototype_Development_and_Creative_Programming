<!DOCTYPE html>
<!-- This is the page for login  -->
<html>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>Login</title>
</head>

<body>
	<p><strong>Please enter your username and password</strong></p>
	<form action="./login_redir.php" method="POST">
		<p>
			<label for="usernameinput" >Username:</label>
			<input type="text" name="username" id="usernameinput">
		</p>
		<p>
			<label for="passwordinput" >Password:</label>
			<input type="password" name="password" id="passwordinput">
		</p>
		<p> <input type="submit" value="Login"> </p>
	</form>
	
	<!-- check if there is error -->
	<p>
		<?php
			session_start();
			if( isset($_SESSION['login_error']))
			{
				if ($_SESSION['login_error'])
				{
					$_SESSION['login_error'] = 0;
					printf("Username or password error.");
                    session_destroy();
				}
			}
            
			
		?>
	</p>
	<p><a href="./register_page.php"> Click here to register </a><p>
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
</html>