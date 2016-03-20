<?php
header("Content-Type: application/json"); // 
session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['user_name']) ){
    $signin_flag = false;
} else {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    require 'database.php';

    $stmt = $mysqli->prepare("select COUNT(*) from users where user_name = ? and user_id = ?");
    if(!$stmt){
		echo json_encode(array(
		"success" => false,
		"message" => "Query Prep Failed: %s\n".$mysqli->error
	));
        exit;
    }

    $stmt->bind_param('si', $user_name, $user_id);
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($cnt);
    $stmt->fetch();
    
    if($cnt == 1 ){
        $signin_flag = true;
    } else {
        $signin_flag = false;
    }
	$stmt->close();
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