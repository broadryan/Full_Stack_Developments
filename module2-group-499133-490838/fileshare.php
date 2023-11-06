<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <h1>AWS File Share</h1>
    <img src="logo.jpg" alt="Picture of logo is supposed to be here">
    
    <form action="fileshare.php" method="post">
    <label for="username">Username: </label>
    <input type="text" id="username" name="username">
    <br><br>
    <input type="submit" value="Log-in" name="login">
    <input type="submit" value="Sign up" name="signup">
    </form>

    <?php
        //Login page
        session_start();
        $file = '/usr/games/module2/users.txt';
        $usernames_string = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING);//
        $usernames_string = file_get_contents($file);
        $usernames_array = explode(',', $usernames_string);
        $usernames_array = array_map('trim', $usernames_array);

    if (isset($_POST['login'])) {
        //File page
        if (in_array(trim($_POST['username']), $usernames_array)) {
            $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING);//
            $username = trim($_POST['username']);
            $_SESSION['username'] = $username;
            $_SESSION['userarray'] = $usernames_array;
            header('Location: filepage.php');
        } else {
            echo '<p style="color:red">Incorrect username</p>';
        }
    }
    if (isset($_POST['signup'])) {
        //Sign up page
        $_SESSION['userarray'] = $usernames_array;
        header('Location: signup.php');
    }

    ?>

  </body>
</html>
