<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
} else {
	$username = 'anonymous';
}

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$votesdb = $db->votes;

$id = $_POST['id'];
$message = $_POST['message'];

$target = array('_id' => new MongoId($id));
$newdata = array('$push' => array("comments"=> array("name"=>$username,"message"=>$message)));
$votesdb->update($target, $newdata);

echo json_encode(array(
	"success" => true,
	"message" => "comment create success."
));

exit;
    
?>