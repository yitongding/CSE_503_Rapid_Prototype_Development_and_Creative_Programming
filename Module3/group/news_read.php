
<!DOCTYPE html>
<html>

<?php
	session_start();
	
	//get news data from database 
	require 'database.php';
	
	$stmt = $mysqli->prepare("select news_title, news_author_id, news_content, news_submit_time, news_timestamp, users.user_name from news join users on (news.news_author_id = users.user_id) where news_id = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $news_id);
	$stmt->execute();
	 
	$stmt->bind_result($news_title, $news_author_id, $news_content, $news_submit_time, $news_timestamp, $news_author_name);
	$stmt->fetch();
	$stmt->close();
?>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>NEWS: <?php echo $newstitle;?> </title>
</head>


<body>
<!-- show the news -->
<p> <?php echo htmlspecialchars($newstitle);?> </p>
<p> Author: <?php echo htmlspecialchars($news_author_name); ?> </p>
<p> Submition time: <?php echo $news_submit_time; ?> </p>
<p> Last edit time: <?php echo $news_timestamp; ?> </p>
<?php
	// allowed orignal author to edit the news
	if ($_SESSION['user_id'] == $news_author_id) {
		printf("<a herf='./news_edit.php?news_id=%d'> Edit this news </a>",$news_id);
	} 
?>
<p> <?php echo htmlspecialchars($news_content); ?> </p>




 <!-- show the comments -->
<p> Comments By Viewers </p>
<?php 
	/*****************************/
	/* show the list of comments */
	/*****************************/
	require 'database.php';
 
	$stmt = $mysqli->prepare("select comment_id, comment_author_id, comment_content, comment_timestamp, users.user_name from comments join users on (comments_author_id = users.user_id) where comment_news_id = ? order by comment_timestamp");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->execute();
	 
	$stmt->bind_result($comment_id, $comment_author_id, $comment_content, $comment_timestamp, $comment_author_name);
	
	//comment number counter
	$cnt = 1;
	
	echo "<ul>\n";
	//show the comments
	while($stmt->fetch()){
		printf("<li><p> %d. %s post on %s </p>", 
			$cnt, htmlspecialchars($comment_author_name),$comment_timestamp);
		if($_SESSION['user_id'] == $comment_author_id) {
			printf("<p><a herf='./comment_edit.php?comment_id=%d'>edit this comment </a></p>", $comment_id);
		}
		printf("<p>%s</p></li>", htmlspecialchars($comment_content));
	}
	echo "</ul>\n";
	 
	$stmt->close();

?>












</body>
</html>