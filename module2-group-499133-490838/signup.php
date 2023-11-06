<!DOCTYPE html>
<html lang="en">
    <head>
    <title>My File Sharing App</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>
  <body>
    <?php
    session_start();
    $usernames_array = $_SESSION['userarray'];

    echo '<form action="signup.php" method="post">';
    echo "<h1>Sign Up</h1>";
    echo '<label for="newuser">Enter new username: </label>';
    echo '<br><br>';
    echo '<input type="text" id="newuser" name="newuser">';
    echo '<input type="submit" value="Create" name="create">';
    echo '<br><br>';
    echo '<input type="submit" value="Homepage" name="homepage">';
    echo '</form>';

    if (isset($_POST['create'])) {
        //File page
        //$newuser = trim($_POST['newuser']);
        $newuser = trim(filter_input(INPUT_POST, 'newuser', FILTER_SANITIZE_STRING));
        
        if (in_array($newuser, $usernames_array)) {
            echo '<p style="color:red">Username already exists. Choose different name.</p>';
        } else {
            file_put_contents("/usr/games/module2/users.txt", "," . $newuser, FILE_APPEND);
            $dir = sprintf("/usr/games/module2/%s", $newuser);
            $permit = 0777;
            mkdir($dir);
            chmod($dir, $permit);
            
            echo '<p style="color:green">Username created. Go back to previous page to login.</p>';
        }
    }

    if (isset($_POST['homepage'])) {
        header('Location: fileshare.php');
    }

    ?>
</body>
</html>