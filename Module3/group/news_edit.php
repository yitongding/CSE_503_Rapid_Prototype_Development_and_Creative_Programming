<!--this is the edting page of news-->

<!DOCTYPE html>
<html>
<head>
	<title>News Editing</title>
</head>
<body>
<?php
	session_start();
	
	if (empty($_GET['news_id']) ){
		header("Location: ./main_page.php");
		exit;
	}
	
	if (isset($_SESSION['news_edit_error'])) {
		if ($_SESSION['news_edit_error'] == 1) {
			$_SESSION['news_edit_error'] = 0;
			printf("<p>News edit error.</p>");
		}
	}
	
	$news_id = $_GET['news_id'];
	//get news data from database 
	require 'database.php';
    
	$stmt = $mysqli->prepare("select news_title, news_author_id, news_content, news_submit_time, news_timestamp, users.user_name, news_link from news join users on (news.news_author_id = users.user_id) where news_id = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i', $news_id);
	$stmt->execute();
	 
	$stmt->bind_result($news_title, $news_author_id, $news_content, $news_submit_time, $news_timestamp, $news_author_name, $news_link);
	$stmt->fetch();
	$stmt->close();

?>


<?php if (!isset($_SESSION['user_id'])): ?>
<!-- check sign in -->
	<p>click <a href='./login_page.php'>here</a> to login to edit a news.</p>
	
	
<?php elseif ($_SESSION['user_id'] != $news_author_id): ?>
<!-- check owner -->
	<p>You are not the author of the news.</p>
	
	
<?php else: ?>
	<form action="./news_update.php" method="POST">
		<!-- title -->
		<p>
			<label for="news_titleinput" >News title:</label>
			<input type="text" name="news_title" id="news_titleinput" size="50" maxlength="100" value="<?php echo  htmlspecialchars($news_title)?>" required>
		</p>
		<!-- URL -->
		<p>
			<label for="news_linkinput" >News URL:</label>
			<input type="text" name="news_link" id="news_linkinput" size="50" maxlength="120" value="<?php echo  htmlspecialchars($news_link)?>">
		</p>
		<!-- content -->
		<p>
			<textarea name="news_content" rows="20" cols="50" maxlength="1000"><?php echo  htmlspecialchars($news_content)?></textarea>
		</p>
		<!-- submit -->
		<input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token'])?>">
		<input type="hidden" name="news_id" value="<?php echo htmlspecialchars($news_id)?>">
		<p> <input type="submit" value="Submit"> </p>
	</form>
<?php endif; ?>

	<!-- return to the news -->
	<p><a href="./news_read.php?news_id=<?php echo htmlspecialchars($news_id) ?>"> Return to news </a></p>

	<!-- return to main page -->
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
	
</html>