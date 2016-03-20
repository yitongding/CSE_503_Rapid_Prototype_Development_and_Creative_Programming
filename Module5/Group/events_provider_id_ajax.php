<?php
// events_provider_ajax.php
header("Content-Type: application/json");

session_start();
/*$token = $_POST['token'];
if ($_SESSION['token'] != $token){
    echo json_encode(array(
        "success" => false,
        "message" => "Token wrong."
    ));
    exit;
}*/

$event_id = $_POST['event_id'];

require 'database.php';

$stmt = $mysqli->prepare("select COUNT(*), event_title, event_date, event_tag from events where event_id = ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
    ));
	$stmt->close();
    exit;
}
$stmt->execute();
$stmt->bind_result($cnt, $event_id, $event_title, $event_date, $event_tag);

while($stmt->fetch()){
	$json_data = array(
		"eid" => $event_id,
		"title" => $event_title,
		"date" => $event_date,
		"tag" => $event_tag
	);
}
$stmt->close();

echo json_encode($json_data);
exit;
?>