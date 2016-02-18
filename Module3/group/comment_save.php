<?php
	session_start();
	
	// prevent directly visit
	if (empty($_POST['news_id']) | !isset($_SESSION['user_id']) ){
		header("Location: ./main_page.php");
		exit;
	}
	
	if($_SESSION['token'] !== $_POST['token']){
		die("Request forgery detected");
	}	
	
	$comment_content = $_POST['comment_content'];
    $news_id = $_POST['news_id'];
	date_default_timezone_set('America/Chicago');
	//$current_time = date('Y-m-d H:i:s');
	
	//insert comment to the databse
	require 'database.php';
	
	$stmt = $mysqli->prepare("insert into comments ( comment_news_id, comment_author_id, comment_content) values (?, ?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('iis', $news_id, $_SESSION['user_id'],$comment_content);
	 
	if ( $stmt->execute() ){
        $stmt->close();
		header("Location: ./news_read.php?news_id=".$news_id);
		exit;
	}
	else{
		//register fail, return to register page
		$stmt->close();
		$_SESSION['comment_submit_error'] = 1;
		header("Location: ./comment_submit.php?news_id=".$news_id);
		exit;
	}
	//echo $news_id;
    //echo $comment_content;
    //echo $_SESSION['user_id'];
	$stmt->close();
	
	
	
?>