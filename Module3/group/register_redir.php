<?php
	session_start();
	
	if (empty($_POST['username']) ){
		header("Location: ./main_page.php");
		exit;
	}
	
	$user_name = $_POST['username'];
	$user_password = $_POST['password'];
	$user_slated_pw = crypt($user_password);
	
	require_once __DIR__ . '/recaptcha/autoload.php';
	$siteKey = '6LfNkxgTAAAAANeO0L7apDDAyhwLCH1oS-uwYOyb';
	$secret = '6LfNkxgTAAAAAKfnVaGcwJMK7dvOMmAowN1JCI4x';
	$lang = 'en';
	
	if (isset($_POST['g-recaptcha-response'])){
		$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	}
    else {
		header("Location: ./main_page.php");
		exit;
	}
	
    if ($resp->isSuccess()){
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
				$_SESSION['register_error'] = 2;
				header("Location: ./register_page.php");
				exit;
			}
		}
		else{
			//register fail, return to register page
			$_SESSION['register_error'] = 1;
			header("Location: ./register_page.php");
			exit;
		}
	}
	else {
		//register fail, return to register page
		$_SESSION['register_error'] = 3;
		header("Location: ./register_page.php");
		exit;
	}
		
		
	
	 
	
	
	
	
?>