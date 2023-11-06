<?php

    if (isset($_POST['submit']))
    {
        require 'database.php';
        session_start();
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

        $username = (string) $_POST['userInput'];

        if(!preg_match('/^[\w_\-\ ]+$/', $username))
        {
            echo "Invalid username";
            exit;
        }

        // Use a prepared statement
        $stmt = $mysqli->prepare("SELECT COUNT(*), user_id, user_pass  FROM users WHERE username = ?");

        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        
        // Bind the parameter
        $stmt->bind_param('s', $username);     
        $stmt->execute();

        // Bind the results
        $stmt->bind_result($cnt, $user_id, $pwd_hash);
        $stmt->fetch();

        $pwd_guess = $_POST['passInput'];

        // Compare the submitted password to the actual password hash
        if($cnt == 1 && password_verify($pwd_guess, $pwd_hash))
        {
            // Login succeeded!
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;

            // Redirect to your target page
            header('Location: home.php');
        } 
        else
        {
            // Login failed; redirect back to the login screen
            echo '<script>alert("Log In FAILED")</script>';
            session_destroy();
        }
    }
    else
    {
        header('Location: login.php');
        session_destroy();
    }
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title> Login Results </title>
    <link rel="stylesheet" href="homeFile.css">
</head>

<body>
    <!-- Click here to return home after results -->
    <form action = "login.php">
        <input type = "submit" value = "Try Again"/>
    </form>
</body>
</html>
