<?php
// Content of database.php
 
$mysqli = new mysqli('localhost', 'root', 'dyt920820', 'petdb');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>