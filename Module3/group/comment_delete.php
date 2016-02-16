<!DOCTYPE html>
<html>
<head>
	<title>Comment Delete</title>
</head>

<body>
<?php
	session_start();
	
	$comment_id = $_GET['comment_id'];
	//get comment data from database 
	require 'database.php';
    
	$stmt = $mysqli->prepare("select comment_author_id, comment_news_id from comments where comment_id = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i', $comment_id);
	$stmt->execute();
	 
	$stmt->bind_result($comment_author_id, $comment_news_id);
	$stmt->fetch();
	$stmt->close();
	
	
	$user_id = $_SESSION['user_id'];
	
	if ($user_id != $comment_author_id) {
		echo "<p>You are not the author of the comment.</p>";
	}
	else {
		date_default_timezone_set('America/Chicago');
		//$current_time = date('Y-m-d H:i:s');
		
		//delete comment from the databse
		require 'database.php';
		
		$stmt = $mysqli->prepare("delete from comments where comment_id=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		 
		$stmt->bind_param('i', $comment_id);
		 
		if ( $stmt->execute() ){
			echo "Delete Success.";
		}
		else{
			//delete fail, return to error page
			echo "Delete Unsuccess.";
		}
		$stmt->close();
	}
	
	
	
?>
	<p><a href="./news_read.php?news_id=<?php echo htmlspecialchars($comment_news_id) ?>"> Return to news </a></p>
	
</body>
	
</html>
