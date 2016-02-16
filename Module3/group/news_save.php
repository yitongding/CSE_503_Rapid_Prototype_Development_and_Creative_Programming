<?php
	session_start();
	
	if (empty($_POST['news_title']) | !isset($_SESSION['user_id']) ){
		header("Location: ./main_page.php");
		exit;
	}
	
	$news_title = $_POST['news_title'];
	$news_content = $_POST['news_content'];
    $news_link = $_POST['news_link'];
	date_default_timezone_set('America/Chicago');
	$current_time = date('Y-m-d H:i:s');
	
	//insert news to the databse
	require 'database.php';
	
	$stmt = $mysqli->prepare("insert into news (news_title, news_author_id, news_content, news_submit_time, news_link) values (?, ?, ?, ?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('sisss', $news_title, $_SESSION['user_id'],$news_content, $current_time, $news_link);
	 
	if ( $stmt->execute() ){
		//if insert success, query news id from database
		$stmt = $mysqli->prepare("SELECT LAST_INSERT_ID();");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->execute();
		$stmt->bind_result($news_id);
		$stmt->fetch();
		$stmt->close();
		header("Location: ./news_read.php?news_id=".$news_id);
		exit;
	}
	else{
		//register fail, return to register page
		$stmt->close();
		$_SESSION['news_submit_error'] = 1;
		header("Location: ./news_submit.php");
		exit;
	}
	 
	$stmt->close();
	
	
	
?>