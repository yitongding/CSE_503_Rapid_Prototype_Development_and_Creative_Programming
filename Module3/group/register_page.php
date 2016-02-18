<!DOCTYPE html>
<!-- This is the page for register  -->
<html>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>Register</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
	<p><strong>Please enter your username and password</strong></p>
	<form action="./register_redir.php" method="POST">
		<p>
			<label for="usernameinput" >New Username:</label>
			<input type="text" name="username" id="usernameinput" maxlength="20" required>
		</p>
		<p>
			<label for="passwordinput" >New Password:</label>
			<input type="password" name="password" id="passwordinput" maxlength="20" required>
		</p>
		<div class="g-recaptcha" data-sitekey="6LfNkxgTAAAAANeO0L7apDDAyhwLCH1oS-uwYOyb"></div>
		<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl='en'">
        </script>
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
				if ($_SESSION['register_error'] == 3)
				{
					printf("Please finish the captcha.");
                    session_destroy();
				}
			}
            
			
		?>
	</p>
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
</html>