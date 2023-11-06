<!DOCTYPE HTML>
<html lang="en">

<head>
    <title> Create Account </title>
</head>

<body>
    
    <h1> Create Account </h1>
    <!-- Input form for users to make a new username -->
    <form action = "signup_results.php" method = "POST">
        Username: <input type = "text" name = "usernameInput"/>
        Password: (8 characters minimum)<input type = "password" name = "passwordInput" minlength="8" required/>
        <input type = "submit" name = "Create" value = "Create User"/>
    </form>

    <br>

    <form action = "home.php">
        <input type = "submit" value = "Return Home"/>
    </form>
</body>
</html>