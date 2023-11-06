<?php
//This PHP script deletes an event from the database based on the information passed in from a form via a POST request. The user's ID is obtained from the current session, and the event's title, date, and time are obtained from the form and sanitized using the escapeInput() function. The code then prepares a DELETE statement to remove the event from the database, and if it executes successfully, it echoes 'success'. Otherwise, it echoes an error message. Finally, the prepared statement and database connection are closed.
session_start();
include 'mod5_database.php';

function escapeInput($input) {
  return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

$event_title = escapeInput($_POST['event_title']);
$event_date = escapeInput($_POST['event_date']);
$event_time = escapeInput($_POST['event_time']);
$user_id = $_SESSION['user_id'];

$query = "DELETE FROM events WHERE user_id = ? AND title = ? AND event_date = ? AND event_time = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("isss", $user_id, $event_title, $event_date, $event_time);

if ($stmt->execute()) {
  echo 'success';
} else {
  echo 'Error: ' . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
