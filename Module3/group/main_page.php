<!DOCTYPE html>
<html>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>NEWS: Main Page</title>
</head>

<body>
<?php
	session_start();
	
	/* first line */
	if (isset($_SESSION['user_id'])) {
		// if loged in
		printf ("
		<p> 
			Hello, <strong>%s</strong>!
			<a href='./logout.php'> Logout </a>
		</p>
		", htmlentities($_SESSION['user_name']);
	}
	else {
		// if not loged in
		printf("
		<p>
			<a href='./login_page.php'> Login </a>
			<a href='./register_page.php'> Register </a>
		</p>
		")
	}
	
	
	
	/* news submit */
	printf("
	<p>
		<a href='./news_submit.php'>Want to submit your own story?</a>
	<p>
	")
	
	
	/*************************/
	/* show the list of news */
	/*************************/
	require 'database.php';
 
	$stmt = $mysqli->prepare("select news_title, news_timestamp from news order by news_timestamp");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->execute();
	 
	$stmt->bind_result($news_title, $news_timestamp);
	 
	echo "<ul>\n";
	while($stmt->fetch()){
		printf("\t<li><a href='./file_read.php?file=%s'>%s</a> %s</li>\n",
			htmlspecialchars($news_title),
			htmlspecialchars($news_title),
			htmlspecialchars($news_timestamp)
		);
	}
	echo "</ul>\n";
	 
	$stmt->close();

?>


</body>

</html>