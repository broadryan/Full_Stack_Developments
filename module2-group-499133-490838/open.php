<?php
session_start();
$username = $_SESSION['username'];
$user_dir = "/usr/games/module2/" . $username;
$selected_file = $user_dir . '/' . $_GET['file'];
$file_type = mime_content_type($selected_file);
header("Content-Type: $file_type");
header('content-disposition: inline; filename="' . $_GET['file'] . '";');
readfile($selected_file);
exit;
?>

