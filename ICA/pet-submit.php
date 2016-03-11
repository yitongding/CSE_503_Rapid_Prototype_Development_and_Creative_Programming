<?php
	session_start();
	
    
	if (empty($_POST['name'])){
		die("Request forgery detected");
	}
	
	
	$name = $_POST['name'];
	$species = $_POST['species'];
    $weight = $_POST['weight'];
	$description = $_POST['description'];
	$filename = basename($_FILES['picture']['name']);
    
    //insert pets to the databse
	require 'database.php';
    $stmt = $mysqli->prepare("insert into pets (name, species, filename, weight, description) values (?, ?, ?, ?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->bind_param('ssdss', $name, $species,$weight, $description, $filename);
	 
	if ( $stmt->execute() ){
        $stmt->close();
		//if insert success
		$full_path = sprintf("../ica/%s", $filename);
        
        if( move_uploaded_file($_FILES['picture']['tmp_name'], $full_path) ){
            if (chmod ($full_path, 777))
            {
                header("Location: ./pet-listing.php");
                exit;
            }
            else
            {
                die("pic upload fail.");
            } 
        }
	}
	else{
		//fail and die
        $stmt->close();
        die("upload exe error.");
	}
	 
	
	
	
	
?>