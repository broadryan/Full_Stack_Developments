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
$usernames_array = $_SESSION['userarray'];
$_SESSION['username'] = $username;
$user_dir = "/usr/games/module2/" . $username;
//$files = scandir($user_dir);
$files = array_map('htmlspecialchars', scandir($user_dir));


if (isset($_POST['open'])) {
    $selected_file = $user_dir . '/' . $_POST['file'];
    header('Location: open.php?file=' . $_POST['file']);
    exit;
}

if (isset($_POST['delete'])) {
    $selected_file = $user_dir . '/' . $_POST['file'];
    unlink($selected_file);
    header("Refresh:0");
    exit;
}

if (isset($_POST['upload'])) {
    //$filename = $_POST['Upload File'];
    $filename = filter_input(INPUT_POST, 'Upload File', FILTER_SANITIZE_STRING);
    $_SESSION['Upload File'] = $filename;
    header('Location: uploadfile.php');
    exit;
}

if (isset($_POST['logout'])) {
    header('Location: fileshare.php');
    session_destroy();
}

if (isset($_POST['sendto'])) {
    //$recipient = trim($_POST['to']);
    $recipient = trim(filter_input(INPUT_POST, 'to', FILTER_SANITIZE_STRING));

    if (in_array($recipient, $usernames_array)) {
        //$filename = $_POST['file'];
        $filename = htmlspecialchars($_POST['file']);
        $recipient_dir = sprintf("/usr/games/module2/%s/%s", $recipient, $filename);
        $selected_file = $user_dir . '/' . $filename;
        copy($selected_file, $recipient_dir);
        echo '<p style="color:green">File sent!</p>';
    } else {
        echo '<p style="color:red">Incorrect username</p>';
    }

}

if (strlen($username) != 0 ){
    echo '<form action="" method="post">';
    echo "<h2>Files for user: $username</h2>";
    echo "<table>";
    echo "<tr>";
    echo "<th>Select</th>";
    echo "<th>File Name</th>";
    echo "</tr>";
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        echo "<tr>";
        echo "<td><input type='radio' name='file' value='$file'></td>";
        echo "<td>$file</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br><br>";
    echo "<input type='submit' value='Open' name='open'>";
    echo "<input type='submit' value='Delete' name='delete'>";
    echo "<input type='submit' value='Logout' name='logout'>";
    echo '<br><br>';

    echo '<label for="to">Enter username to send file to: </label>';
    echo '<input type="text" id="to" name="to">';
    echo '<br><br>';
    echo  '<input type="submit" value="Send" name="sendto">';
    echo '</form>';

    echo '<form enctype="multipart/form-data" action="uploadfile.php" method="POST">';
    echo '<p>';
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />';
    echo '<label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />:';
    echo '</p>';
    echo '<p>';
    echo '<input type="submit" value="Upload File" />';
    echo '</p>';
    echo '</form>';
}
else{
    echo "Session expired.";
    echo '<br>';
    echo '<form action="" method="post">';
    echo '<input type="submit" value="Homepage" name="homepage">';
    echo '</form>';

    if (isset($_POST['homepage'])) {
        header('Location: fileshare.php');
    }
}


?>
</body>
</html>
