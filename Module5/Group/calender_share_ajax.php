<?php
//calender_share_ajax.php

header("Content-Type: application/json");
session_start();

function get_share_user_re($str){
	$user_regex = "/\b([\w]+)(?:;([\w]+))*\b/";
	if(preg_match($user_regex, $str, $matches)){
		return $matches;
	} else return false;
}

$share = $_POST['share'];
$share_array = get_share_user_re($share);
$share_user1_id = $_SESSION['user_id'];
$token = $_POST['token'];

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
		$stmt = $mysqli->prepare("select COUNT(*), user_id from users where user_name = ?");	//check if the user is in the DB
        if(!$stmt){
            echo printf("Query Prep Failed: %s\n", $mysqli->error);
            echo json_encode(array(
                "success" => false,
                "quiry" => 1,
                "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
            ));
            exit;
        }
		$stmt->bind_param('s', $share_user_name);
		$stmt->execute();
		$stmt->bind_result($cnt, $share_user_id);
		$stmt->fetch();
		
		if($cnt != 1 ){
			echo json_encode(array(
				"success" => false,
				"message" => $share_user_name . "does not exist."
			));
			exit;
		}
		
		$share_user_id_ary[$i-1] = $share_user_id;
		$stmt->close();
	}
	
	for ($i = 1; $i < $share_num; $i++){
		//check if the pair is already exist
		$share_user_id = $share_user_id_ary[$i-1];
		$stmt = $mysqli->prepare("select COUNT(*) from share where share_user1_id = ? and share_user2_id = ?");
		if(!$stmt){
            echo json_encode(array(
                "user_id" => $share_user_id,
                "success" => false,
                "quiry" => 2,
                "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
            ));
            exit;
        }
		$stmt->bind_param('ss', $share_user1_id, $share_user_id);
		$stmt->execute();
		$stmt->bind_result($cnt);
		$stmt->fetch();
		
		if ($cnt != 0) {
			echo json_encode(array(
                "success" => false,
                "message" => " Calender already shared with one of the users."
            ));
			$stmt->close();
            exit;
		}
		$stmt->close();
	}
	
	for ($i = 1; $i < $share_num; $i++){
		$share_user_id = $share_user_id_ary[$i-1];
		$stmt = $mysqli->prepare("insert into share (share_user1_id, share_user2_id) values (?, ?)");
		if(!$stmt){
            echo json_encode(array(
                "user_id" => $share_user_id,
                "success" => false,
                "quiry" => 2,
                "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
            ));
            exit;
        }
		$stmt->bind_param('ss', $share_user1_id, $share_user_id);
		$stmt->execute();
		$stmt->close();
	}
	echo json_encode(array(
                "success" => true,
                "message" => "Calender share success."
            ));
	exit;
} else {
	echo json_encode(array(
		"success" => false,
		"message" => "user name input format wrong."
	));
	exit;
}
?>