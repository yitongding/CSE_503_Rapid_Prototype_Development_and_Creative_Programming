<?php
// login_ajax.php
 
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

require 'database.php';

if (empty($_POST['username']) ){
    header("Location: ./main_page.php");
    exit;
}

$stmt = $mysqli->prepare("select COUNT(*), user_id, user_slated_pw from users where user_name = ?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

// Bind the parameter
$stmt->bind_param('s', $user);
$user = $_POST['username'];
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id, $pwd_hash);
$stmt->fetch();

$pwd_guess = $_POST['password'];

// Compare the submitted password to the actual password hash
if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
    // Login succeeded!
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $_POST['username'];
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

?>
