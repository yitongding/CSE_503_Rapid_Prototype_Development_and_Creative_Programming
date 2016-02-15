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
	<form action="./news_save.php" method="POST">
		<!-- title -->
		<p>
			<label for="news_titleinput" >News' title:</label>
			<input type="text" name="news_title" id="news_titleinput" size="50" maxlength="100" >
		</p>
		<!-- content -->
		<p>
			<textarea name="news_content" rows="20" cols="50" maxlength="1000">Please type your story's content here.</textarea>
		</p>
		<!-- submit -->
		<p> <input type="submit" value="Submit"> </p>
	</form>
	
	<!-- return to main page -->
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
	
</html>