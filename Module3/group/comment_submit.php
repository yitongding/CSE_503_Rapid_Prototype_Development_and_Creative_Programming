<!--this is the submittion page of news-->

<!DOCTYPE html>
<html>
<head>
	<title>Comment Submittion</title>
</head>
<body>
<?php
	session_start();
	
	// prevent directly visit
	if (empty($_GET['news_id']) ){
		header("Location: ./main_page.php");
		exit;
	}
	
	$news_id=$_GET['news_id'];
	if (isset($_SESSION['comment_submit_error'])) {
		if ($_SESSION['comment_submit_error'] == 1) {
			$_SESSION['comment_submit_error'] = 0;
			printf("<p>comment submit error.</p>");
		}
	}
	if (!isset($_SESSION['user_id'])){
		printf("<p>click <a href='./login_page.php'>here</a> to login to submit a comment.</p>");
	}
	elseif (isset($news_id)){
		printf('
			<form action="./comment_save.php" method="POST">
				<!-- news id(hiden input)-->
				<input type="hidden" name="news_id" value=%d>
				<!-- content -->
				<p>
					<textarea name="comment_content" rows="3" cols="50" maxlength="140">Please type your comment here.</textarea>
				</p>
				<input type="hidden" name="token" value="%s">
				<!-- submit -->
				<p> <input type="submit" value="Submit"> </p>
			</form>
		', $news_id, $_SESSION['token']);
	}
	echo "<!-- return to the news-->";
	echo "<p><a href='./news_read.php?news_id=".$news_id."'>return to the news</a></p>";
?>
	
    
    
	<!-- return to main page -->
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
	
</html>