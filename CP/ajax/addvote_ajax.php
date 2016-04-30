<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$votesdb = $db->votes;

$id = $_POST['id'];
$opt_name = $_POST['opt_name'];
$anonymous = $_POST['anonymous'];

if ($anonymous == "true") {
	$username = 'anonymous';
}else {
	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
	} else {
		$username = 'anonymous';
	}
}


$target = array('_id' => new MongoId($id));
$newdata = array('$inc' => array("votecount" => 1));
$votesdb->update($target, $newdata);

$target = array('_id' => new MongoId($id),"options.name"=> $opt_name);
$newdata = array('$inc' => array("options.$.count" => 1));
$votesdb->update($target, $newdata);

$target = array('_id' => new MongoId($id));
$newdata = array('$push' => array("comments"=> array("name"=>$username,"message"=>"vote for ".$opt_name)));
$votesdb->update($target, $newdata);

echo json_encode(array(
	"success" => true,
	"message" => "vote create success."
));

exit;
    
?>