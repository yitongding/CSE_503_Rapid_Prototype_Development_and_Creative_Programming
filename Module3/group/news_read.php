
<!DOCTYPE html>
<html>

<?php
	session_start();
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
	
	//echo "userid".$_SESSION['user_id'];
	//echo "author_id".$news_author_id;
	
	/*begin of the html*/
	printf("<head>\n<title>NEWS:%s </title>\n</head>\n<body>\n<!-- show the news -->\n",htmlspecialchars($news_title));

	/*body starts here*/
	printf ("<p>%s   ",htmlspecialchars($news_title));
	// allowed orignal author to edit the news
    if (isset($_SESSION['user_id'])){
        if ($_SESSION['user_id'] == $news_author_id) {
	       printf("<a href='./news_edit.php?news_id=%d'> Edit this news </a>\n",$news_id);
	   } 
    }
	
	echo "</p>\n"; 
	echo "<p> Author: ".htmlspecialchars($news_author_name)."    ";
	echo "Submition time: ".$news_submit_time."   ";
	echo "Last edit time: ".$news_timestamp."</p>\n";
    
    //show the URL
    if (is_null($news_link)) {
        echo "<p>No link for this news.</p><br/>";
    }
    else{
        echo "<p><a href=".htmlspecialchars($news_link)."URL:".htmlspecialchars($news_link)."</a></p><br/>";
    }
    //show content
	echo "<p>".htmlspecialchars($news_content)."</p>\n";




    //show the comments
	echo "<br/><br/><br/><p> Comments By Viewers </p><br/>";

	/*****************************/
	/* show the list of comments */
	/*****************************/
	require 'database.php';
 
	$stmt = $mysqli->prepare("select comment_id, comment_author_id, comment_content, comment_timestamp, users.user_name from comments join users on (comment_author_id = users.user_id) where comment_news_id = ? order by comment_timestamp");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->execute();
	 
	$stmt->bind_result($comment_id, $comment_author_id, $comment_content, $comment_timestamp, $comment_author_name);
	
	//comments number counter
	$cnt = 1;
	
	echo "<ul>\n";
	//show the comments
	while($stmt->fetch()){
		printf("<li><p> %d. %s post on %s </p>", 
			$cnt, htmlspecialchars($comment_author_name),$comment_timestamp);
		if($_SESSION['user_id'] == $comment_author_id) {
			printf("<p><a href='./comment_edit.php?comment_id=%d'>edit this comment </a></p>", $comment_id);
		}
		printf("<p>%s</p></li>", htmlspecialchars($comment_content));
	}
	echo "</ul>\n";
	 
	$stmt->close();
    
    printf("<a href='./comment_submit.php?news_id=%d'>Submit a commment</a>", $news_id);
    
?>

</body>
</html>