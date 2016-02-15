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
        if (!isset($_SESSION['user_id'])){
            printf("<p>click <a href='./login_page.php'>here</a> to login to submit a comment.</p>");
        }
        else {
            printf('
                <form action="./comment_save.php" method="POST">
                    <!-- content -->
                    <p>
                        <textarea name="news_content" rows="3" cols="50" maxlength="140">Please type your comment here.</textarea>
                    </p>
                    <!-- submit -->
                    <p> <input type="submit" value="Submit"> </p>
                </form>
            ');
        }
	?>
	
	<!-- return to main page -->
	<p><a href="./main_page.php"> Return to Main-page </a><p>
</body>
	
</html>