<!DOCTYPE html>
<html>
    <head>
        <title>Redirecting, please wait.</title>
    </head>
    
	<body>
		<?php
			session_start();
			session_destroy();
			header("Location: ./main_page.php");
			exit;
		?>
	</body>
	
</html>