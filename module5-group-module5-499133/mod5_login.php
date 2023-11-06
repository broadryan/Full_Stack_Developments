<?php
//This is a PHP script that handles the login process. It starts the session and includes the database connection file. The function escapeInput is defined to sanitize user input. The user's input for the username and password are retrieved using the POST method and sanitized using the escapeInput function. The regular expressions are used to validate the username and password inputs.

The script then prepares a SELECT statement to retrieve the user's id and hashed password from the database based on the provided username. The password is verified using password_verify() function. If the password matches, the username and user_id are stored in the session variables, and "success" is echoed. If the password does not match, "Invalid username or password" is echoed. Finally, the prepared statement and database connection are closed.
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

$query = "SELECT user_id, user_pass FROM users WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id, $user_pass);
$stmt->fetch();

if (password_verify($password, $user_pass)) {
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $user_id;
    echo "success";
} else {
    echo "Invalid username or password";
}

$stmt->close();
$mysqli->close();
?>
