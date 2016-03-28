<?php
//events_editer_ajax.php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

function get_share_user_re($str){
	$email_regex = "/\b([\w]+)(?:;([\w]+))*\b/";
	if(preg_match($email_regex, $str, $matches)){
		return $matches;
	} else return false;
}

$eid = $_POST['eid'];
$title = $_POST['title'];
$date = $_POST['date'];
$tag = $_POST['tag'];
$share = $_POST['share'];
$token = $_POST['token'];
$user_id = $_SESSION['user_id'];
$share_array = get_share_user_re($share);

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
	"message" => "Query Prep Failed: %s\n".$mysqli->error,
    "quiry" => 1
));
	exit;
}

$stmt->bind_param('i', $eid);
$stmt->execute();

// Bind the results
$stmt->bind_result($event_user_id);
$stmt->fetch();

if($event_user_id != $user_id ){
	echo json_encode(array(
        "success" => false,
        "message" => "user id mismatch."
    ));
	$stmt->close();
    exit;
}
$stmt->close();

/**********************************/
//check if there is share user
/**********************************/
if ($share_array != false){   
	$share_num = count($share_array);
	for ($i = 1; $i < $share_num; $i++){	//for every shared user
		$share_user_name = $share_array[$i];
		$stmt = $mysqli->prepare("select COUNT(*), user_id from users where user_name = ?");	//check if the user is in the database
		$stmt->bind_param('s', $share_user_name);
		$stmt->execute();
		$stmt->bind_result($cnt, $share_user_id);
		$stmt->fetch();
		
		if($cnt != 1 ){
			echo json_encode(array(
				"success" => false,
				"message" => $share_user_name + "does not exist."
			));
			$stmt->close();
			exit;
		}
		
		$share_user_id_ary[$i-1] = $share_user_id;
        $stmt->close();
	}
	
	//after make sure all users are in the database, insert them
	for ($i = 1; $i < $share_num; $i++){
		$share_user_id = $share_user_id_ary[$i-1];
		$stmt = $mysqli->prepare("insert into events (event_user_id, event_title, event_date, event_tag) values (?, ?, ?, ?)");
		
		$stmt->bind_param('isss', $share_user_id, $title, $date, $tag);
		$stmt->execute();
	}
}

/**********************************/
//update the event
/**********************************/
$stmt = $mysqli->prepare("update events set event_title = ?, event_date = ?, event_tag = ? where event_id = ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error),
        "quiry" => 3
    ));
    exit;
}
$stmt->bind_param('sssi',$title, $date, $tag, $eid);

if ($stmt->execute()) {
    echo json_encode(array(
        "success" => true,
        "message" => "Event update success."
    ));
	$stmt->close();
    exit;
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Event update fail."
    ));
	$stmt->close();
    exit;
}
    
?>