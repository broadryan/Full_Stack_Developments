<?php
//This code retrieves events from the database for a given date, belonging to the logged in user, and returns them in JSON format. The input date is sanitized using the escapeInput function. A prepared statement is used to prevent SQL injection attacks. The result of the query is stored in an array of associative arrays, with each sub-array representing an event. Finally, the array is encoded in JSON format and echoed to the client.
session_start();
include 'mod5_database.php';

function escapeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

$date = escapeInput($_POST['date']);
$user_id = $_SESSION['user_id'];

$query = "SELECT title, event_date, event_time FROM events WHERE user_id = ? AND event_date = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$stmt->bind_result($title, $event_date, $event_time);

$events = array();
while ($stmt->fetch()) {
    $events[] = array(
        'title' => $title,
        'event_date' => $event_date,
        'event_time' => $event_time
    );
}


echo json_encode($events);

$stmt->close();
$mysqli->close();
?>
