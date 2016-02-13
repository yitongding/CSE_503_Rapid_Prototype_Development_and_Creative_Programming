<?php
// Content of database.php
 
$mysqli = new mysqli('localhost', 'php_user', 'php_user', 'module3');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>