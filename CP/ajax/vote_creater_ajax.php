<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$votesdb = $db->votes;

$name = $_POST['name'];
$option1 = $_POST['option1'];
$option2 = $_POST['option2'];
$option3 = $_POST['option3'];
$option4 = $_POST['option4'];
$option5 = $_POST['option5'];
$username = $_SESSION['username'];

$dbInput = array(
	"name" => $name,
	"host" => $username,
	"endflag" => false,
	"comments" => array(),
	"votecount" => 0,
	"options" => array(
		array( "name" => $option1, "count" => 0),
		array( "name" => $option2, "count" => 0),
		array( "name" => $option3, "count" => 0),
		array( "name" => $option4, "count" => 0),
		array( "name" => $option5, "count" => 0)
	)
);

$votesdb->insert($dbInput);

echo json_encode(array(
	"success" => true,
	"message" => "vote create success."
));

exit;
    
?>