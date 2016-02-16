<!--this is the edting page of news-->

<!DOCTYPE html>
<html>
<head>
	<title>Comment Editing</title>
</head>
<body>
<?php
	session_start();
	if (isset($_SESSION['comment_edit_error'])) {
		if ($_SESSION['comment_edit_error'] == 1) {
			$_SESSION['comment_edit_error'] = 0;
			printf("<p>comment edit error.</p>");
		}
	}
	
	$comment_id = $_GET['comment_id'];
	//get comment data from database 
	require 'database.php';
    
	$stmt = $mysqli->prepare("select comment_author_id, comment_content, comment_news_id from comments where comment_id = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i', $comment_id);
	$stmt->execute();
	 
	$stmt->bind_result($comment_author_id, $comment_content, $news_id);
	$stmt->fetch();
	$stmt->close();

?>


<?php if (!isset($_SESSION['user_id'])): ?>
<!-- check sign in -->
	<p>click <a href='./login_page.php'>here</a> to login to edit a comment.</p>
	
	
<?php elseif ($_SESSION['user_id'] != $comment_author_id): ?>
<!-- check owner -->
	<p>You are not the author of the comment.</p>
	
	
<?php else: ?>
	<form action="./comment_update.php" method="POST">
		<!-- content -->
		<p>
			<textarea name="comment_content" rows="3" cols="50" maxlength="140"><?php echo htmlspecialchars($comment_content) ?></textarea>
		</p>
		<!-- news id(hiden input)-->
		<input type="hidden" name="news_id" value="<?php echo htmlspecialchars($news_id)?>">
		<input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment_id)?>">
		<!-- submit -->
		<p> <input type="submit" value="Submit"> </p>
	</form>
<?php endif; ?>


	<!-- return to the news -->
	<p><a href="./news_read.php?news_id=<?php echo htmlspecialchars($news_id) ?>"> Return to news </a></p>
	<!-- return to main page -->
	<p><a href="./main_page.php"> Return to Main-page </a></p>
</body>
	
</html>