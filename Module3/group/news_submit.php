<!--this is the submittion page of news-->

<!DOCTYPE html>
<html>
<head>
	<title>News Submittion</title>
</head>
<body>
<?php
	session_start();
	if (isset($_SESSION['news_submit_error'])) {
		if ($_SESSION['news_submit_error'] == 1) {
			$_SESSION['news_submit_error'] = 0;
			printf("<p>News submit error.</p>");
		}
	}
?>
<?php if (!isset($_SESSION['user_id'])): ?>
	<p>click <a href='./login_page.php'>here</a> to login to submit a news.</p>
<?php else: ?>
	<form action="./news_save.php" method="POST">
		<!-- title -->
		<p>
			<label for="news_titleinput" >News title:</label>
			<input type="text" name="news_title" id="news_titleinput" size="50" maxlength="100" required>
		</p>
		<!-- URL -->
		<p>
			<label for="news_linkinput" >News URL:</label>
			<input type="url" name="news_link" id="news_linkinput" size="50" maxlength="120" >
		</p>
		<!-- content -->
		<p>
			<textarea name="news_content" rows="20" cols="50" maxlength="1000">Please type your story content here.</textarea>
		</p>
		<!-- submit -->
		<input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token'])?>">
		<p> <input type="submit" value="Submit"> </p>
	</form>
<?php endif; ?>
	<!-- return to main page -->
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
	
</html>