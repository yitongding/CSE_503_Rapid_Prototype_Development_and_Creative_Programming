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

$year = $_POST['year'];
$month = $_POST['month'];
$user_id = $_SESSION['user_id'];

$events_time_ub = $year."-".$month."-01 00:00:00";
$events_time_lb = $year."-".$month."-31 23:59:59";

require 'database.php';

$stmt = $mysqli->prepare("select COUNT(*), event_id, event_title, event_date, event_tag from users where event_user_id = ? and time between ? and ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error);
    ));
    exit;
}
$stmt->bind_param("iss", $user_id, $events_time_ub, $events_time_lb);
$stmt->execute();
$stmt->bind_result($cnt, $event_id, $event_title, $event_date, $event_tag);

$json_data = array();
while($stmt->fetch()){
	$event = array(
		"eid" => $event_id,
		"title" => $event_title,
		"date" => $event_date,
		"tag" => $event_tag
	);
	array_push($json_data, $event);
}

echo json_encode($json_data);
exit;
?>