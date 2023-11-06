<?php
//This PHP code snippet starts by starting a session and including a separate PHP file for database connection. It defines a function named escapeInput which takes input as an argument and returns the input with any special characters encoded to prevent SQL injection attacks.
session_start();
include 'mod5_database.php';

function escapeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

$title = escapeInput($_POST['title']);
$date = escapeInput($_POST['date']);
$time = escapeInput($_POST['time']);
$user_id = $_SESSION['user_id'];

$query = "INSERT INTO events (user_id, title, event_date, event_time) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("isss", $user_id, $title, $date, $time);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>

