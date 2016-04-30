<?php
	session_start();

	$vote_title = $_POST['vote_title'];
    $vote_host = $_SESSION['username'];
    $vote_option1 = $_POST['option1'];
    $vote_option2 = $_POST['option2'];
    $vote_option3 = $_POST['option3'];
    $vote_option5 = $_POST['option5'];
    $vote_option4 = $_POST['option4'];
	
	//insert vote to the databse
	require 'database.php';
	
	$stmt = $mysqli->prepare("insert into vote (vote_title, vote_author_id, vote_content, vote_submit_time, vote_link) values (?, ?, ?, ?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('sisss', $vote_title, $_SESSION['user_id'],$vote_content, $current_time, $vote_link);
	 
	if ( $stmt->execute() ){
		//if insert success, query vote id from database
		$stmt = $mysqli->prepare("SELECT LAST_INSERT_ID();");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->execute();
		$stmt->bind_result($vote_id);
		$stmt->fetch();
		$stmt->close();
		header("Location: ./vote_read.php?vote_id=".$vote_id);
		exit;
	}
	else{
		//register fail, return to register page
		$stmt->close();
		$_SESSION['vote_submit_error'] = 1;
		header("Location: ./vote_submit.php");
		exit;
	}
	 
	$stmt->close();
	
	
	
?>