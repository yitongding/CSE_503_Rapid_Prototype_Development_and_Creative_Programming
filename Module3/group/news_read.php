
<!DOCTYPE html>
<html>

<?php
	session_start();
	
	if (empty($_GET['news_id']) ){
		header("Location: ./main_page.php");
		exit;
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
	
	//echo "userid".$_SESSION['user_id'];
	//echo "author_id".$news_author_id;
	
	/*begin of the html*/
	printf("<head>\n<title>NEWS:%s </title>\n</head>\n<body>\n<!-- show the news -->\n",htmlspecialchars($news_title));
	
	/******************/
	/*body starts here*/
	/******************/
	echo '<!-- return to main page --> 
	<p><a href="./main_page.php"> Return to Main-page </a><p>';
	printf ("<p>%s &nbsp",htmlspecialchars($news_title));
	// allowed orignal author to edit the news
    if (isset($_SESSION['user_id'])){
        if ($_SESSION['user_id'] == $news_author_id) {
	       printf("<a href='./news_edit.php?news_id=%d'>Edit</a> &nbsp\n",$news_id);
		   printf("<a href='./news_delete.php?news_id=%d'> Delete</a>\n",$news_id);
	   } 
    }
	
	echo "</p>\n"; 
	echo "<p> Author: ".htmlspecialchars($news_author_name)."&nbsp;";
	echo "Submition time: ".$news_submit_time."&nbsp;";
	echo "Last edit time: ".$news_timestamp."</p>\n";
    
    //show the URL
    if (is_null($news_link)) {
        echo "<p>No link for this news.</p><br/>";
    }
    else{
        echo "<p>URL:<a href='".htmlspecialchars($news_link)."'>".htmlspecialchars($news_link)."</a></p><br/>";
    }
    //show content
	echo "<p>".htmlspecialchars($news_content)."</p>\n";




    //show the comments
	echo "<br/><br/><br/><p> <strong>Comments By Viewers </strong></p>";
	printf("<a href='./comment_submit.php?news_id=%d'>Submit a commment</a>", $news_id);
	
	/*****************************/
	/* show the list of comments */
	/*****************************/
	require 'database.php';
 
	$stmt = $mysqli->prepare("select comment_id, comment_author_id, comment_content, comment_timestamp, users.user_name from comments join users on (comment_author_id = users.user_id) where comment_news_id = ? order by comment_timestamp");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i', $news_id);
	$stmt->execute();
	 
	$stmt->bind_result($comment_id, $comment_author_id, $comment_content, $comment_timestamp, $comment_author_name);
	
	//comments number counter
	$cnt = 1;
	
	echo "<ul>\n";
	//show the comments
	while($stmt->fetch()){
		printf("<li><p> %d. %s post on %s </p>", 
			$cnt, htmlspecialchars($comment_author_name),$comment_timestamp);
        if(isset($_SESSION['user_id'])){
            if($_SESSION['user_id'] == $comment_author_id) {
                printf("<p><a href='./comment_edit.php?comment_id=%d'>Edit</a> &nbsp", $comment_id);
				printf("<a href='./comment_delete.php?comment_id=%d'>Delete </a></p>", $comment_id);
            }
        }
		
		printf("<p>%s</p></li>", htmlspecialchars($comment_content));
        $cnt = $cnt + 1;
	}
	echo "</ul>\n";
	 
	$stmt->close();
    
?>


</body>
</html>