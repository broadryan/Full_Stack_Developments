<?php 
//This is a PHP script that updates an existing event in the database. It starts by including the database connection file and defining a function to escape user input. The script then retrieves the new and old event details from the POST request and the session user ID.

Next, the script creates an SQL statement to update the event with the new details for the specified user and old details. It prepares the statement and binds the parameters to prevent SQL injection attacks.

If the update is successful, it outputs "success". Otherwise, it outputs an error message. Finally, it closes the statement and the database connection.
session_start();
include 'mod5_database.php';

function escapeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

$new_title = escapeInput($_POST['new_title']);
$new_date = escapeInput($_POST['new_date']);
$new_time = escapeInput($_POST['new_time']);
$user_id = $_SESSION['user_id'];
$old_title = escapeInput($_POST['old_title']);
$old_date = escapeInput($_POST['old_date']);
$old_time = escapeInput($_POST['old_time']);




$query = "UPDATE events SET title=?, event_date=?, event_time=? WHERE user_id=? AND title=? AND event_date=? AND event_time=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sssisis", $new_title, $new_date, $new_time, $user_id, $old_title, $old_date, $old_time);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'Error updating event: ' . $mysqli->error;
}

$stmt->close();
$mysqli->close();
?>
