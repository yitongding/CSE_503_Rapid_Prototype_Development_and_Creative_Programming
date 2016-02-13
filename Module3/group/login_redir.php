//
<?php
	session_start();
	
	$username_list = fopen("../module2/users.txt","r");
	
	include './user_validation.php';
	if (!user_validation($_POST['username']))
	{
		$_SESSION['username_error'] = 1;
		header("Location: ./login_page.php");
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
