<?php
header("Content-Type: application/json"); // 

$user_name = $_POST['username'];
$user_password = $_POST['password'];
$user_slated_pw = crypt($user_password);

require 'database.php';
		
$stmt = $mysqli->prepare("insert into users (user_name, user_slated_pw) values (?, ?)");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
    
$stmt->bind_param('ss', $user_name, $user_slated_pw);
    
if ( $stmt->execute() ){
    //if insert success, query user id from database
    $stmt = $mysqli->prepare("select COUNT(*), user_id from users where user_name = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $user);
    $user = $_POST['username'];
    $stmt->execute();
    
    // Bind the results
    $stmt->bind_result($cnt, $user_id);
    $stmt->fetch();
    $stmt->close();
    if( $cnt == 1 ){
        // Login succeeded!
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['token'] = substr(md5(rand()), 0, 10);

        echo json_encode(array(
            "success" => true,
            "username" => $user
        ));
        exit;	
    }else{
        echo json_encode(array(
            "success" => false,
            "message" => "Incorrect Username or Password"
        ));
        exit;
    }
}
else{
    //register fail
    echo json_encode(array(
        "success" => false,
        "message" => "Incorrect Username or Password"
    ));
    exit;
}

?>