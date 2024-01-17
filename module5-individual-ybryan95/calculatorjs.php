<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Calculator</title>
  </head>
  <body>
    <!-- Two input fields for numbers -->
    <div>
      <input type="number" id="num1">
      <input type="number" id="num2">
    </div>
    <!-- Radio buttons for choosing operation -->
    <div>
      <input type="radio" id="add" name="operation" value="add">
      <label for="add">Add</label>
      <input type="radio" id="subtract" name="operation" value="subtract">
      <label for="subtract">Subtract</label>
      <input type="radio" id="multiply" name="operation" value="multiply">
      <label for="multiply">Multiply</label>
      <input type="radio" id="divide" name="operation" value="divide">
      <label for="divide">Divide</label>
    </div>
    <!-- Display area for result -->
    <div>
      <label>Result:</label>
      <span id="result"></span>
    </div>
    <!-- JavaScript code -->
    <script>
      // Get references to input fields and result span
      const num1Input = document.getElementById('num1');
      const num2Input = document.getElementById('num2');
      const operationInputs = document.getElementsByName('operation');
      const resultSpan = document.getElementById('result');

      // Function to calculate and display result
      function calculateResult() {
        // Get values of input fields
        const num1 = parseFloat(num1Input.value);
        const num2 = parseFloat(num2Input.value);

        let result;
        for (const operationInput of operationInputs) {
          // Check which radio button is checked and perform corresponding operation
          if (operationInput.checked) {
            switch (operationInput.value) {
              case 'add':
                result = num1 + num2;
                break;
              case 'subtract':
                result = num1 - num2;
                break;
              case 'multiply':
                result = num1 * num2;
                break;
              case 'divide':
                if (num2 === 0) { // Handle division by zero error
                  result = NaN;
                  resultSpan.textContent = 'Error: Division By Zero';
                  return;
                }
                result = num1 / num2;
                break;
            }
          }
        }

        // Display result or error message
        resultSpan.textContent = isNaN(result) ? 'Invalid Input' : result;
      }

      // Add event listeners to input fields and radio buttons
      num1Input.addEventListener('keyup', calculateResult);
      num2Input.addEventListener('keyup', calculateResult);
      for (const operationInput of operationInputs) {
        operationInput.addEventListener('change', calculateResult);
      }
    </script>
  </body>
</html>
