<?php
	session_start();
	
	$comment_content = $_POST['comment_content'];
	date_default_timezone_set('America/Chicago');
	//$current_time = date('Y-m-d H:i:s');
	
	//insert comment to the databse
	require 'database.php';
	
	$stmt = $mysqli->prepare("insert into comments ( comment_news_id, comment_author_id, comment_content) values (?, ?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('iis', $news_id, $_SESSION['user_id'],$commment_content);
	 
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