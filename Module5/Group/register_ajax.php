<?php
header("Content-Type: application/json"); // 
ini_set("session.cookie_httponly", 1);
session_start();

$mongo = new MongoClient();
$db = $mongo->vote;
$userdb = $db->users;

$username = $_POST['username'];
$password = $_POST['password'];
$hash

$dbInput=array(
	"username" => $username,
	
);

$userdb->insert($dbInput);


    
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
            "username" => $user,
            "token" => $_SESSION['token']
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