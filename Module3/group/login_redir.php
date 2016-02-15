<?php
	session_start();
	
	require 'database.php';
	
	$stmt = $mysqli->prepare("select COUNT(*), user_id, user_slated_pw from users where user_name = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	
	// Bind the parameter
	$stmt->bind_param('s', $user);
	$user = $_POST['username'];
	$stmt->execute();

	// Bind the results
	$stmt->bind_result($cnt, $user_id, $pwd_hash);
	$stmt->fetch();

	$pwd_guess = $_POST['password'];
	
	// Compare the submitted password to the actual password hash
	if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
		// Login succeeded!
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_name'] = $_POST['username'];
		$_SESSION['login_error'] = 0;

		// Redirect to your target page
		header("Location: ./main_page.php");
		exit;	
	}
	else{
		// Login failed; redirect back to the login screen
		$_SESSION['login_error'] = 1;
		header("Location: ./login_page.php");
		exit;
	}
	
?>
