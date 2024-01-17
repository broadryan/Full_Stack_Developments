<!DOCTYPE html>
<html lang = "en">
<head>
  <title>Calculator</title>
</head>
<body>
  <h1>Calculator</h1>
  <!-- Create a form that accepts user input -->
  <form method="get">
  <!-- Label for the first input field -->
  <label for="num1">First number:</label>

  <!-- Input field for the first number.
  The value of the input field is set using a PHP ternary operator,
  which checks if the GET parameter 'num1' is set and if it is, it sets the value of the input field to it,
  otherwise it sets it to an empty string -->
  <input type="text" name="num1" id="num1" value="<?php echo isset($_GET['num1']) ? htmlspecialchars($_GET['num1']) : ''; ?>" required>
  <br>
  <!-- Label for the second input field -->
  <label for="num2">Second number:</label>
  <!-- Input field for the second number.-->
  <input type="text" name="num2" id="num2" value="<?php echo isset($_GET['num2']) ? htmlspecialchars($_GET['num2']) : ''; ?>" required>
  <br>
  <!-- Radio buttons for the operators. Similar logic so that elements
  remain in the input even after "calculate" button is pressed.-->
  <input type="radio" name="operator" value="add" <?php echo (isset($_GET['operator']) && $_GET['operator'] == 'add') ? 'checked' : ''; ?>> Addition<br>
  <input type="radio" name="operator" value="subtract" <?php echo (isset($_GET['operator']) && $_GET['operator'] == 'subtract') ? 'checked' : ''; ?>> Subtraction<br>
  <input type="radio" name="operator" value="multiply" <?php echo (isset($_GET['operator']) && $_GET['operator'] == 'multiply') ? 'checked' : ''; ?>> Multiplication<br>
  <input type="radio" name="operator" value="divide" <?php echo (isset($_GET['operator']) && $_GET['operator'] == 'divide') ? 'checked' : ''; ?>> Division<br>
  <br>
  <!-- Submit button -->
  <input type="submit" value="Let's Calculate!">
  </form>
    <!-- PHP code block starts. Executed when num1, num2, and radio type is selected -->
  <?php
  if (isset($_GET['num1']) && isset($_GET['num2'])) {
    // Store the values of num1 and num2 in variables
    $num1 = $_GET['num1'];
    $num2 = $_GET['num2'];
    // Store the operator selected in the form in a variable
    $operator = $_GET['operator'];
    
    // Use a switch statement to perform the appropriate calculation based on the operator selected
    switch ($operator) {
      case 'add':
        $result = $num1 + $num2;
        break;
      case 'subtract':
        $result = $num1 - $num2;
        break;
      case 'multiply':
        $result = $num1 * $num2;
        break;
      case 'divide':
        // Check if division by 0 is attempted and set an error message if it is
        if ($num2 == 0) {
          $result = "ERROR: Division by 0";
        } else {
          $result = $num1 / $num2;
        }
        break;
    }
  }
  ?>
  <!-- This block of code displays the result if it has been set -->
  <?php if (isset($result)) : ?>
    <h2>Result:</h2>
    <!-- Display the result of the calculation -->
    <p><?php echo $result; ?></p>
  <?php endif; ?>
</body>
</html>
