<?php
// events_provider_ajax.php
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$mongo = new MongoClient("mongodb://localhost");
$db = $mongo->vote;
$votesdb = $db->votes;



$dbOutput = $votesdb->find();
$json_data = array();
foreach ($dbOutput as $document) {
    $vote = array(
		"id" => $document["_id"],
		"name" => $document["name"],
		"host" => $document["host"],
		"votecount" => $document["votecount"],
		"endflag" => $document["endflag"]
	);
	array_push($json_data, $vote);
}

echo json_encode($json_data);
exit;
?>