<!DOCTYPE html>
<html lang="en">
    <head>
    <title>My File Sharing App</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>
  <body>
<?php
session_start();
$username = $_SESSION['username'];
$filename = $_FILES['uploadedfile']['name'];
$filetype = filter_input(INPUT_POST, 'filetype', FILTER_SANITIZE_STRING);

//$full_path = sprintf("/usr/games/module2/%s/%s", $username, $filename);
$full_path = sprintf("/usr/games/module2/%s/%s.%s", $username, $filename, $filetype);
$upload_ok = move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path);

//if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
	if ($upload_ok){
	echo 'file successfully uploaded!';
	echo '<br><br>';
	echo '<a href="javascript:history.go(-1)">Go Back...</a>';
	exit;
}else{
	echo 'file upload failed';
	echo '<br><br>';
	echo '<a href="javascript:history.go(-1)">Go Back...</a>';
	exit;
}

?>
</body>
</html>