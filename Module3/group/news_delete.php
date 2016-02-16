<!DOCTYPE html>
<html>
<head>
	<title>News Delete</title>
</head>

<body>
<?php
	session_start();
	
	$news_id = $_GET['news_id'];
	//get comment data from database 
	require 'database.php';
    
	$stmt = $mysqli->prepare("select news_author_id from news where news_id = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i', $news_id);
	$stmt->execute();
	 
	$stmt->bind_result($news_author_id);
	$stmt->fetch();
	$stmt->close();
	
	
	$user_id = $_SESSION['user_id'];
	
	if ($user_id != $news_author_id) {
		echo "<p>You are not the author of the news.</p>";
	}
	else {
		date_default_timezone_set('America/Chicago');
		//$current_time = date('Y-m-d H:i:s');
		
		
		require 'database.php';
		//delete comments on this news from the databse
		$stmt = $mysqli->prepare("delete from comments where comment_news_id=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $news_id);
		$stmt->execute();
		
		//delete the news
		$stmt = $mysqli->prepare("delete from news where news_id=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $news_id);
		
		if ( $stmt->execute() ){
			echo "Delete Success.";
			echo '<!-- return to main page --> 
	<p><a href="./main_page.php"> Return to Main-page </a><p>';
		}
		else{
			//delete fail, return to error page
			echo "Delete Unsuccess.<br/>";
			echo '<p><a href="./news_read.php?news_id='.htmlspecialchars($news_id).'"> Return to news </a></p>';
		}
		$stmt->close();
	}
	
	
	
?>
	
	
</body>
	
</html>
