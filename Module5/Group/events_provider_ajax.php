<?php
// events_provider_ajax.php
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
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
$month = $_POST['month'] + 1;
if (strlen($month) == 1){
    $month = "0".$month;
}
$user_id = $_SESSION['user_id'];
$show_user_name = $_POST['show_user_name'];

$events_time_ub = $year."-".$month."-01";
$events_time_lb = $year."-".$month."-31";

require 'database.php';

$stmt = $mysqli->prepare("select COUNT(*), r.user_name from users as u, share as s, users as r  where s.share_user1_id = u.user_id and u.user_id = ? and s.share_user2_id = r.user_id");
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => printf("Query Prep Failed: %s\n", $mysqli->error)
	));
	$stmt->close();
	exit;
}
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($cnt, $share_user_name);
$share_name_array = array($cnt);
while ($stmt->fetch() & $cnt != 0){
	array_push($share_name_array, $share_user_name);
}
$stmt->close();

//if show shared calender
if ($show_user_name != $_SESSION['user_name'] & $show_user_name != "MySelf") {
	$stmt = $mysqli->prepare("select COUNT(*), user_id from users, share  where share_user1_id = user_id and user_name = ? and share_user2_id = ?");
	if(!$stmt){
		echo json_encode(array(
			"success" => false,
			"message" => printf("Query Prep Failed: %s\n", $mysqli->error)
		));
		$stmt->close();
		exit;
	}
	$stmt->bind_param("ss", $show_user_name, $user_id);
	$stmt->execute();
	$stmt->bind_result($cnt, $share_user_id);
	$stmt->fetch();
	$stmt->close();
	if ($cnt == 1){
		$user_id = $share_user_id;
	} else {
		echo json_encode(array(
			array(
				"signin" => true,
				"valid_share" => false,
				"date" => null,
				"user_id" => $user_id,
				"date_ub" => $events_time_ub,
				"date_lb" => $events_time_lb
			)
		));
		exit;
	}
}

$stmt = $mysqli->prepare("select event_id, event_title, event_date, event_tag from events where event_user_id = ? and event_date between ? and ?");
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
$stmt->bind_result($event_id, $event_title, $event_date, $event_tag);

$json_data = array(
	array(
		"signin" => true,
		"date" => null,
        "user_id" => $user_id,
		"valid_share" => true,
        "date_ub" => $events_time_ub,
        "date_lb" => $events_time_lb,
		"share_name_array" => $share_name_array
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