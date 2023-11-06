<?php
//This PHP script starts a session and includes a file for connecting to a database. It retrieves the user ID from the session, and then selects all event dates from the events table in the database that match the user ID. The dates are stored in an array, which is then encoded as JSON and printed to the screen. Finally, the statement and database connection are closed. This code is likely used to retrieve a list of event dates for a particular user in order to display them on a calendar or other interface.
session_start();
include 'mod5_database.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT event_date FROM events WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($event_date);

$dates = array();

while ($stmt->fetch()) {
    $dates[] = $event_date;
}

echo json_encode($dates);

$stmt->close();
$mysqli->close();
?>

