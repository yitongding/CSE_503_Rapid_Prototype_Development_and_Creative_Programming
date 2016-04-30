<?php
header("Content-Type: application/json"); // 
ini_set("session.cookie_httponly", 1);
session_start();

if (empty($_SESSION['username']) ){
    $signin_flag = false;
} else {
    $user_name = $_SESSION['username'];
	$signin_flag = true;
}

if ($signin_flag) {
    echo json_encode(array(
		"success" => true,
        "username" => $user_name
	));
    exit;	
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}

?>