<!-- The DOCTYPE declaration defines the document type and version of HTML being used -->
<!DOCTYPE HTML>
<!-- The HTML tag is the root element of an HTML page -->
<html lang="en">
<head>
    <!-- The title element sets the title of the document -->
    <title>Calendar</title>
    <!-- The link element is used to link to external stylesheets -->
    <link rel="stylesheet" href="mod5_homeFile.css">
    <!-- The script element is used to load a JavaScript file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://classes.engineering.wustl.edu/cse330/content/calendar.min.js"></script>
    <script src="mod5_calendar.js"></script>
    <script src="mod5_auth.js"></script>
</head>
<body>
  <!-- The h1 element defines a large heading -->
  <h1>AWS Calendar</h1>
  <!-- Start a PHP session -->
  <?php 
    session_start();
    // If the user is logged in, display a logout button
    if(isset($_SESSION['username'])){
      echo "<form action='mod5_logout.php'><input type='submit' value='Log Out'/></form>";
      // If there is no CSRF token, generate one and store it in the session
      if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
      }
    } else {
  ?>
  <!-- If the user is not logged in, display login and signup forms -->
  <label id="login-label">Login</label>
  <form id="login-form">
    <label>Username:</label>
    <input type="text" id="login-username" name="username"><br>
    <label>Password:</label>
    <input type="password" id="login-password" name="password"><br>
    <input type="submit" value="Login">
    <div id="login-error"></div>
  </form>
  <label id="signup-label">Sign Up</label>
  <form id="signup-form">
    <label>Username:</label>
    <input type="text" id="signup-username" name="username"><br>
    <label>Password (At least 8 characters):</label>
    <input type="password" id="signup-password" name="password"><br>
    <input type="submit" value="Sign Up">
    <div id="signup-error"></div>
  </form>
  <?php } ?>
  <!-- Line break elements are used to create additional space between elements -->
  <br><br>
  <!-- Create a script tag and set the CSRF token as a JavaScript variable -->
  <script>
  var csrf_token = '<?php echo $_SESSION['csrf_token']; ?>';
 </script>
  <!-- If the user is logged in, display a welcome message and buttons to add/edit/view events -->
  <?php if (isset($_SESSION['username'])) { ?>
  <h2>Welcome <?php echo $_SESSION['username'] ?>!</h2>
  <p>Select the day of the calendar to add a new event (the color of the day will turn <span style="color: yellow">yellow</span>), 
  or to edit/delete/view the details of an existing event (border color <span style="color: blue"> blue</span>) of the chosen day. Click 
  the corresponding button after selection. <em>Note: The calendar may take some time to load</em></p>

  <button id="add-event">Add</button>
  <button id="view-edit-event">View/Edit</button>

  <?php } ?>

  <div id="calendar-navigation">
        <button id="prev-month">&lt;</button>
        <span id="calendar-month"></span>
        <button id="next-month">&gt;</button>
    </div>
    <div id="calendar-container"></div>

</body>
</html>
