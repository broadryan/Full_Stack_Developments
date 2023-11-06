<?php
    require 'database.php';
    if (isset($_POST["Create"]))
    {
        $username = (string) $_POST["usernameInput"];
        $password = (string) $_POST["passwordInput"];

        // First check if username and password are valid
        if(!preg_match('/^[\w_\-\ ]+$/', $username))
        {
            echo "Invalid username";
        }
        else if (!preg_match('/^[\w_\-\ ]+$/', $password))
        {
            echo "Invalid password";
        }
        else
        {
            if (strlen($username) > 0 && strlen($password) > 0)
            {
                // Check if database entry exists in 'users'
                $stmt = $mysqli->prepare("select username, user_pass from users where username = '$username'");
                
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $stmt->execute();
                $result = $stmt->get_result();
                
                // If no entries found through query, then it's unique username
                if ($result->num_rows == 0)
                {
                    // Insert new entry into table users
                    $stmt_write = $mysqli->prepare("insert into users (username, user_pass) values (?, ?)");
                    if (!$stmt_write)
                    {
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }

                    $stmt_write->bind_param('ss', $username, password_hash($password, PASSWORD_DEFAULT));
                    $stmt_write->execute();
                    $stmt_write->close();

                    echo "<strong> Congratulations! Your new account has been created. </strong>"; // Congratulatory message on successful account creation
                }
                else
                {
                    // Duplicate usernames are rejected
                    echo "<strong> Username exists already: Try again! </strong>";
                }

                $stmt->close();
            } 
            else
            {
                // Redirect back to signup page if submission was wrong
                header("Location: signup.php");
            }
        }
    }
    else
    {
        // Redirect to signup page if you didn't submit anything
        header("Location: signup.php");
    }
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title> Account Creation Results </title>
    <link rel="stylesheet" href="homeFile.css">
</head>

<body>
    <!-- Click here to return home after results -->
    <form action = "home.php">
        <input type = "submit" value = "Return Home"/>
        <input type = "hidden" name = "token" value = "<?php echo $_SESSION['token'];?>" />
    </form>

    <!-- Click here to return to create account screen to make another one -->
    <form action = "signup.php">
        <input type = "submit" value = "Create Account"/>
        <input type = "hidden" name = "token" value = "<?php echo $_SESSION['token'];?>" />
    </form>
</body>
</html>