<?php
session_start();

require 'database.php';

if ($_POST('loginsub')=="Login") {
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
		$_SESSION['token'] = substr(md5(rand()), 0, 10);

		// Redirect to your target page
		header("Location: ./list.php");
		exit;	
	}
	else{
		// Login failed; redirect back to the login screen
		$_SESSION['login_error'] = 1;
		header("Location: ./list.php");
		exit;
	}
}


else {
	
	$user_name = $_POST['username'];
	$user_password = $_POST['password'];
	$user_slated_pw = crypt($user_password);

	//insert slated password and username into database
	require 'database.php';
	
	$stmt = $mysqli->prepare("insert into users (user_name, user_slated_pw) values (?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
		
	$stmt->bind_param('ss', $user_name, $user_slated_pw);
		
	if ( $stmt->execute() ){
		//if insert success, query user id from database
		$stmt = $mysqli->prepare("select COUNT(*), user_id from users where user_name = ?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('s', $user);
		$user = $_POST['username'];
		$stmt->execute();
		
		// Bind the results
		$stmt->bind_result($cnt, $user_id);
		$stmt->fetch();
		$stmt->close();
		if( $cnt == 1 ){
			// Login succeeded!
			$_SESSION['user_id'] = $user_id;
			$_SESSION['user_name'] = $user_name;
			$_SESSION['token'] = substr(md5(rand()), 0, 10);
			$_SESSION['login_error'] = 0;

			// Redirect to your main page
			header("Location: ./main_page.php");
			exit;	
		}
		else{
			// Login failed; redirect back to the login screen
			$_SESSION['login_error'] = 2;
			header("Location: ./list.php");
			exit;
		}
	}
	else{
		//register fail, return to register page
		$_SESSION['login_error'] = 1;
		header("Location: ./list.php");
		exit;
	}
}

?>
