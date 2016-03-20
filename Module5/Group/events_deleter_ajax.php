<?php
//events_deleter_ajax.php

header("Content-Type: application/json");

$eid = $_POST['eid'];
$token = $_POST['token'];
$user_id = $_SESSION['user_id'];

if ($_SESSION['token'] != $token){
    echo json_encode(array(
        "success" => false,
        "message" => "Token wrong."
    ));
    exit;
}

require 'database.php';

/**********************************/
//check if the user owns the event
/**********************************/
$stmt = $mysqli->prepare("select event_user_id from events where event_id = ?");
if(!$stmt){
	echo json_encode(array(
	"success" => false,
	"message" => "Query Prep Failed: %s\n".$mysqli->error
));
	exit;
}

$stmt->bind_param('i', eid);
$stmt->execute();

// Bind the results
$stmt->bind_result($event_user_id);
$stmt->fetch();

if($event_user_id != $user_id ){
	echo json_encode(array(
        "success" => false,
        "message" => "user id mismatch."
    ));
    exit;
}

/**********************************/
//update the event
/**********************************/
$stmt = $mysqli->prepare("delete from comments where comment_id=?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error);
    ));
    exit;
}
$stmt->bind_param('i', $eid);

if ($stmt->execute()) {
    echo json_encode(array(
        "success" => true,
        "message" => "Event delete success."
    ));
	$stmt->close();
	exit;
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Event delete fail."
    ));	
	$stmt->close();
	exit;
}



?>
