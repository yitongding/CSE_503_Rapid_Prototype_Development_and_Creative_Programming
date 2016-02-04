<!DOCTYPE html>
<html>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>File system login</title>
<head>

<body>
	<p><strong>Welcome to file system!</strong></p>
	<form action="./login_redir.php" method="POST">
		<p>
			<label for="usernameinput" >Username:</label>
			<input type="text" name="username" id="usernameinput">
			<input type="submit" value="Login">
		</p>
	</form>
	<p>
		<?php
			session_start();
			if( isset($_SESSION['username_error']))
			{
				if ($_SESSION['username_error'])
				{
					printf("Username error.");
                    session_destroy();
				}
			}
            
			
		?>
	</p>
</body>
</html>