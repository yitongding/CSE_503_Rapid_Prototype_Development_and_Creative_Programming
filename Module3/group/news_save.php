<?php
	session_start();
	
	$news_title = $_POST['news_title'];
	$news_content = $_POST['news_content'];
	$current_time = date('Y-m-d H:i:s');
	
	//insert news to the databse
	require 'database.php';
	
	$stmt = $mysqli->prepare("insert into news (news_title, news_author_id, news_content, news_submit_time) values (?, ?, ?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('ssss', $news_title, $_SESSION('user_id'),$news_content, $current_time);
	 
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