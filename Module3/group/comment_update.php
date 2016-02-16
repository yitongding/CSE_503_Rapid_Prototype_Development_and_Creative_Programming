<?php
	session_start();
	
	// prevent directly visit
	if (empty($_POST['news_id']) | !isset($_SESSION['user_id']) ){
		header("Location: ./main_page.php");
		exit;
	}
	
	$comment_content = $_POST['comment_content'];
    $news_id = $_POST['news_id'];
	$comment_id = $_POST['comment_id'];
	
	date_default_timezone_set('America/Chicago');
	//$current_time = date('Y-m-d H:i:s');
	
	//insert comment to the databse
	require 'database.php';
	
	$stmt = $mysqli->prepare("update comments set comment_content=? where comment_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('si', $comment_content, $comment_id);
	 
	if ( $stmt->execute() ){
        $stmt->close();
		header("Location: ./news_read.php?news_id=".$news_id);
		exit;
	}
	else{
		//update fail, return to update page
		$stmt->close();
		$_SESSION['comment_submit_error'] = 1;
		header("Location: ./comment_submit.php?news_id=".$news_id);
		exit;
	}
	$stmt->close();
?>
