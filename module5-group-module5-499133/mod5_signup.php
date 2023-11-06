<?php
//This is a PHP script that handles the sign up functionality of a web application. The script first starts a session and includes a database connection script. It then defines a function to escape user inputs for security purposes. The script receives the username and password from the form data, validates them, and checks if the username already exists in the database. If the username exists, the script outputs an error message and exits. If the username is unique, the script hashes the password and inserts the username and hashed password into the database. The script then sets session variables for the user's username and user ID and outputs "success" to indicate that the sign up process was successful. Finally, the script closes the prepared statements and the database connection.
session_start();
include 'mod5_database.php';

function escapeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

$username = escapeInput($_POST['username']);
$password = escapeInput($_POST['password']);

if (!preg_match('/^[\w_\-\ ]+$/', $username)) {
    echo "Invalid username";
    exit;
}

if (!preg_match('/^[\w_\-\ ]{8,}$/', $password)) {
    echo "Invalid password";
    exit;
}

$query = "SELECT user_id FROM users WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Username already exists";
    $stmt->close();
    $mysqli->close();
    exit;
}

$stmt->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users (username, user_pass) VALUES (?, ?)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $username, $hashed_password);
$stmt->execute();

$_SESSION['username'] = $username;
$_SESSION['user_id'] = $mysqli->insert_id;

echo "success";

$stmt->close();
$mysqli->close();
?>
