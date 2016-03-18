<?php
// events_provider_ajax.php
header("Content-Type: application/json");

$token = $_POST['token'];
if ($_SESSION['token'] != $token){
    echo json_encode(array(
        "success" => false,
        "message" => "Token wrong."
    ));
    exit;
}

$event_id = $_POST['event_id'];

$events_time_ub = $year."-".$month."-01 00:00:00";
$events_time_lb = $year."-".$month."-31 23:59:59";

require 'database.php';

$stmt = $mysqli->prepare("select COUNT(*), event_title, event_date, event_tag from users where event_id = ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error);
    ));
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

echo json_encode($json_data);
exit;
?>