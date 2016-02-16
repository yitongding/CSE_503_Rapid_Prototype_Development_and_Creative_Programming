a<?php
	session_start();
	
	if (empty($_POST['news_title']) | !isset($_SESSION['user_id']) ){
		header("Location: ./main_page.php");
		exit;
	}
	
	$news_title = $_POST['news_title'];
	$news_content = $_POST['news_content'];
    $news_link = $_POST['news_link'];
	$news_id = $_POST['news_id'];
	date_default_timezone_set('America/Chicago');
	$current_time = date('Y-m-d H:i:s');
	
	//insert news to the databse
	require 'database.php';
	
	$stmt = $mysqli->prepare("update news set news_title=?,  news_content=? , news_link=? where news_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('sssi', $news_title,$news_content, $news_link, $news_id);
	 
	if ( $stmt->execute() ){
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