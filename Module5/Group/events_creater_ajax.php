<?php

header("Content-Type: application/json");

function get_share_user_re($str){
	user_regex = "/\b([\w]+)(?:;([\w]+))*\b/";
	if(preg_match($email_regex, $str, $matches)){
		return $matches;
	} else return false;
}

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


if ($share_array != false){   //check if there is share user
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
			exit;
		}
		
		$share_user_id_ary[$i-1] = $share_user_id;
	}
	
	//after make sure all users are in the database, insert them
	for ($i = 1; $i < $share_num; $i++){
		$share_user_id = $share_user_id_ary[$i-1];
		$stmt = $mysqli->prepare("insert into events (event_user_id, event_title, event_date, event_tag) values (?, ?, ?, ?)");
		
		$stmt->bind_param('isss', $share_user_id, $title, $date, $tag);
		$stmt->execute()
	}
}

$stmt = $mysqli->prepare("insert into events (event_user_id, event_title, event_date, event_tag) values (?, ?, ?, ?)");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error);
    ));
    exit;
}
$stmt->bind_param('isss', $user_id, $title, $date, $tag);

if ($stmt->execute()) {
    echo json_encode(array(
        "success" => true,
        "message" => "Event create success."
    ));
    exit;
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Event create fail."
    ));
    exit;
}
    
?>