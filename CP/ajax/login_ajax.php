<?php
// login_ajax.php
 
header("Content-Type: application/json"); 
ini_set("session.cookie_httponly", 1);

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$userdb = $db->users;

$user = $_POST['username'];
$pwd_guess = $_POST['password'];

$dbInput = array("username"=>$user);
$dbOutput = $userdb->find($dbInput);
foreach ($dbOutput as $document) {
    $pwd_hash = $document['password'];
}

// Compare the submitted password to the actual password hash
if( crypt($pwd_guess, $pwd_hash)==$pwd_hash){
    // Login succeeded!
    session_start();
    $_SESSION['username'] = $_POST['username'];

    echo json_encode(array(
		"success" => true,
        "username" => $user,
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
