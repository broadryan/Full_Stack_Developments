<?php 
session_start(); // start the session to access session variables
include 'mod5_database.php'; // include the database connection file

$user_id = $_SESSION['user_id']; // get the user ID from the session variable
$event_id = $_POST['event_id']; // get the event ID from the POST data
$title = $_POST['title']; // get the updated title from the POST data
$date = $_POST['date']; // get the updated date from the POST data
$time = $_POST['time']; // get the updated time from the POST data

$query = "UPDATE events SET title = ?, event_date = ?, event_time = ? WHERE user_id = ? AND event_id = ?"; // prepare the SQL query to update the event
$stmt = $mysqli->prepare($query); // prepare the statement
$stmt->bind_param("ssiii", $title, $date, $time, $user_id, $event_id); // bind the parameters to the statement

if ($stmt->execute()) { // if the statement is executed successfully
    echo "success"; // return a success message
} else { // if there is an error executing the statement
    echo "Error: " . $stmt->error; // return an error message
} 

$stmt->close(); // close the statement
$mysqli->close(); // close the database connection
?> 
