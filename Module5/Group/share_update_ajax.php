<?php
// share_update_ajax.php
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

$user_id = $_SESSION['user_id'];

require 'database.php';

$stmt = $mysqli->prepare("select COUNT(*), r.user_name from users as u, share as s, users as r  where s.share_user2_id = u.user_id and u.user_id = ? and s.share_user1_id = r.user_id");
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
$stmt->fetch();
$share_name_array = array($cnt);
$stmt->close();

$stmt = $mysqli->prepare("select r.user_name from users as u, share as s, users as r  where s.share_user2_id = u.user_id and u.user_id = ? and s.share_user1_id = r.user_id");
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
$stmt->bind_result($share_user_name);

while ($stmt->fetch() & $cnt != 0){
	array_push($share_name_array, $share_user_name);
}
$stmt->close();

$json_data = array(
	array(
		"signin" => true,
		"share_name_array" => $share_name_array
	)
);

echo json_encode($json_data);
exit;

?>