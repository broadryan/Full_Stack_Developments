<!DOCTYPE HTML>
<html lang="en">
  <head>
    <title> Login Page </title>
  </head>
  <body>
    <h1>
        User Login 
    </h1>

    <!-- Input field for username. -->
    <form action = "login_results.php" method = "POST">
        Username: <input type = "text" name = "userInput"/>
        Password: <input type = "password" name = "passInput"/>
        <input type = "submit" name = "submit" value = "Log In" />
        
    </form>

    <br>

    <form action = "home.php">
        <input type = "submit" value = "Return Home" />
    </form>
  </body>
</html>