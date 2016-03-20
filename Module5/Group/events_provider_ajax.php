<?php
// events_provider_ajax.php
header("Content-Type: application/json");
session_start();

if (empty($_SESSION['user_id']))
{
	echo json_encode(array(
		array(
			"signin" => false
		)
	));
	exit;
}

/*$token = $_POST['token'];
if ($_SESSION['token'] != $token){
    echo json_encode(array(
        "success" => false,
        "message" => "Token wrong."
    ));
    exit;
}*/

$year = $_POST['year'];
$month = $_POST['month'];
$user_id = $_SESSION['user_id'];

$events_time_ub = $year."-".$month."-01 00:00:00";
$events_time_lb = $year."-".$month."-31 23:59:59";

require 'database.php';

$stmt = $mysqli->prepare("select COUNT(*), event_id, event_title, event_date, event_tag from events where event_user_id = ? and event_date between ? and ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
    ));
	$stmt->close();
    exit;
}
$stmt->bind_param("iss", $user_id, $events_time_ub, $events_time_lb);
$stmt->execute();
$stmt->bind_result($cnt, $event_id, $event_title, $event_date, $event_tag);

$json_data = array(
	array(
		"signin" => true,
		"date" => null
	)
);

while($stmt->fetch()){
	if ($event_id != null){
		$event = array(
			"eid" => $event_id,
			"title" => $event_title,
			"date" => $event_date,
			"tag" => $event_tag
		);
		array_push($json_data, $event);
	}
}
$stmt->close();

echo json_encode($json_data);
exit;
?>