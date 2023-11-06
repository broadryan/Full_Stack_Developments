<?php
// Content of database.php, taken from course wiki

$mysqli = new mysqli('localhost', 'mod5_admin', '1111', 'calendar');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>