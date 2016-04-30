<?php
// events_provider_ajax.php
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$votesdb = $db->votes;

$id = $_POST['id'];
$dbInput = array("id"=>$id);
$dbOutput = $votesdb->findOne(array('_id' => new MongoId($id)));
if (isset($_SESSION['username'])){
	$dbOutput['username'] = $_SESSION['username'];
} else {
	$dbOutput['username'] = 'anonymous';
}

echo json_encode($dbOutput);

exit;
?>