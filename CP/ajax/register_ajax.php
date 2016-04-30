<?php
header("Content-Type: application/json"); // 
ini_set("session.cookie_httponly", 1);
session_start();

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$userdb = $db->users;

$username = $_POST['username'];
$password = $_POST['password'];
$hash_pw = crypt($password);

$dbInput=array(
	"username" => $username,
	"password" => $hash_pw
);

$userdb->insert($dbInput);
$_SESSION['username'] = $username;
echo json_encode(array(
	"success" => true,
	"username" => $username,
));
exit;
    
?>