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
		", htmlentities($_SESSION['user_name']));
	}
	else {
		// if not loged in
		printf("
		<p>
			<a href='./login_page.php'>Login</a> 
			or 
			<a href='./register_page.php'>Register</a>
		</p>
		");
	}
	
	
	
	/* news submit */
	printf("
	<p>
		<a href='./news_submit.php'>Want to submit your own story?</a>
	<p>
	");
	
	/*************************************/
    /*from for choosing which day to show*/
    /*************************************/
    ?>
    
    <form action="./main_page.php" method="POST">
    <p> 
        <label for="news_showdateinput"> Choose a day to show :</label>
        <input type="date" name="news_showdate" id="news_showdateinput">
        <input type="submit" value="Go">
    </p>
    </form>
    <a href="main_page.php">Show All</a> &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="main_page.php?hot=1">Hot News</a>
    
    <?php
    /*************************/
	/* show the list of news */
	/*************************/
    require 'database.php';
    
    //see if the date is specified
    if ( !empty($_POST['news_showdate']) ) {
        $news_showdate=$_POST['news_showdate'];
        $news_showdate_lb=$news_showdate." 00:00:00";
        $news_showdate_ub=$news_showdate." 23:59:59";
        
        $stmt = $mysqli->prepare("select news_id, news_title, news_submit_time, news_view from news where news_submit_time between ? and ? order by news_submit_time"); 
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param("ss", $news_showdate_lb, $news_showdate_ub);
    }
    elseif (!empty($_GET['hot']) ){
        $stmt = $mysqli->prepare("select news_id, news_title, news_submit_time, news_view from news order by news_view desc"); //order by views
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    }
    else {
        $stmt = $mysqli->prepare("select news_id, news_title, news_submit_time, news_view from news order by news_submit_time desc"); //order by submit time
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    } 
	$stmt->execute();
	 
	$stmt->bind_result($news_id, $news_title, $news_submittime, $news_view);
	 
	echo "<ul>\n";
	while($stmt->fetch()){
		printf("\t<li>
        <p><a href='./news_read.php?news_id=%d'>%s</a></p>
        <p> %s &nbsp;&nbsp;&nbsp;&nbsp; %d &nbsp; viewers</p>
        </li>\n",
			htmlspecialchars($news_id),
			htmlspecialchars($news_title),
			htmlspecialchars($news_submittime),
            htmlspecialchars($news_view)
		);
	}
	echo "</ul>\n";
	 
	$stmt->close();

?>


</body>

</html>