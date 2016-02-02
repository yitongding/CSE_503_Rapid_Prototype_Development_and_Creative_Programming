<!DOCTYPE html>
<html>
    <head>
        <title>Redirecting, please wait.</title>
    </head>
    
	<body>
		<?php
			session_start();
			
			$username_list = fopen("../module2/users.txt","r");
			$user_found = 0;
			while( !feof($username_list) & $user_found == 0)
			{
				$user_temp = fgets($username_list);
				$user_trim = trim($user_temp);
				if ($user_trim == $_POST['username'])
				{
					$user_found = 1;
				}
			}
			fclose($username_list);
			
			if ($user_found == 0)
			{
				$_SESSION['username_error'] = 1;
				header("Location: ./ login.php");
				exit;
			}
			else
			{
				$_SESSION['username_error'] = 0;
				$_SESSION['username'] = $_POST['username'];
				header("Location: ./user_file.php");
				exit;
			}
		?>
	</body>
	
</html>