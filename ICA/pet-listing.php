<!DOCTYPE html>
<head>
<meta charset="utf-8"/>
<title>Pet Listings</title>
<style type="text/css">
body{
	width: 760px; /* how wide to make your web page */
	background-color: teal; /* what color to make the background */
	margin: 0 auto;
	padding: 0;
	font:12px/16px Verdana, sans-serif; /* default font */
}
div#main{
	background-color: #FFF;
	margin: 0;
	padding: 10px;
}
h1{
    text-align: center;
}
</style>
</head>

<body>
    <h1>
        Pet Listings.
    </h1>
    <p><a href='./add-pet.html'>Want to submit your own pet?</a></p>
<?php    
    require 'database.php';
    
    $species_list= array('cat','dog','fish','bird','hamster') ;
    echo "<ul>\n";
    foreach ($species_list as $sp){
        $stmt = $mysqli->prepare("select COUNT(*)from pets where species=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param("s", $sp);
        $stmt->execute();
	 
        $stmt->bind_result($count);
        
        
        while($stmt->fetch()){
            printf("\t<li>
            <p> %s &nbsp;&nbsp;&nbsp;&nbsp; %d</p>
            </li>\n",
                htmlspecialchars($sp),
                htmlspecialchars($count)
            );
        }
    }
    echo "</ul>\n";
    $stmt->close();
    
    require 'database.php';
    
    $stmt = $mysqli->prepare("select species, name, weight, description, filename from pets"); //order by submit time
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_result($species, $name, $weight, $description, $picture);
    
    
	while($stmt->fetch()){
		printf("
        <p><img src='../ICA/%s' alt='%s'></p>
        <ul>\n
        <li><strong>%s</strong></li>
        <li>%s - %s pounds</li>
        <li>%s</li>
        </ul>\n
        ",
			htmlspecialchars($picture),
			htmlspecialchars($picture),
            htmlspecialchars($name),
			htmlspecialchars($species),
            htmlspecialchars($weight),
			htmlspecialchars($description)
		);
	}
	$stmt->close();
    
?>
    

</body>

</html>