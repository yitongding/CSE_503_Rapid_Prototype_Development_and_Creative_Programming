<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$votesdb = $db->votes;

$id = $_POST['id'];
$com_id = $_POST['com_id'];

$target = array('_id' => new MongoId($id));
$dbOutput = $votesdb->findOne($target);
$old_ary = $dbOutput['comments'];
unset( $old_ary[$com_id] );
$new_ary = array_values( $old_ary );

$newdata = array('$set' => array("comments"=> $new_ary));
$votesdb->update($target, $newdata);

echo json_encode(array(
	"success" => true,
	"message" => $new_ary,
	"com_id" => $com_id
));

exit;
    
?>