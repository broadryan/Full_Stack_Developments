// Attach a submit event handler to the login form
$(function() {
  $('#login-form').on('submit', function(e) {
    // Prevent the form from submitting normally
    e.preventDefault();
    // Get the values of the username and password fields
    var username = $('#login-username').val();
    var password = $('#login-password').val();
    // Send an AJAX request to the mod5_login.php script
    $.ajax({
      type: 'POST', // Use the HTTP POST method
      url: 'mod5_login.php', // Send the request to this URL
      data: { username: username, password: password }, // Include the username and password in the request data
      success: function(data) {
        if (data == 'success') {
          // If the login was successful, remove the login and signup forms and labels
          $('#login-form').remove();
          $('#signup-form').remove();
          $('#login-label').remove();
          $('#signup-label').remove();
          // Display a success message and a "Log Out" button
          $('#login-error').text('Login successful!');
          $('<form action="mod5_logout.php"><input type="submit" value="Log Out"/></form>').insertAfter('#login-error');
          // Reload the page
          location.reload();
          
          // Add a message and buttons to the calendar section
          $('<p>Click on the day of the calendar to add a new event or to edit/delete/view the details of an existing event of the chosen day.</p>').insertBefore('#calendar-navigation');
          $('<button id="add-event">Add</button>').insertAfter('p');
          $('<button id="delete-event">Delete</button>').insertAfter('#add-event');
          $('<button id="view-edit-event">View/Edit</button>').insertAfter('#delete-event');
        } else {
          // If the login failed, display an error message
          $('#login-error').text(data);
        }
      }
    });
  });

  // Attach a submit event handler to the signup form
  $('#signup-form').on('submit', function(e) {
    // Prevent the form from submitting normally
    e.preventDefault();
    // Get the values of the username and password fields
    var username = $('#signup-username').val();
    var password = $('#signup-password').val();
    // Send an AJAX request to the mod5_signup.php script
    $.ajax({
      type: 'POST', // Use the HTTP POST method
      url: 'mod5_signup.php', // Send the request to this URL
      data: { username: username, password: password }, // Include the username and password in the request data
      success: function(data) {
        if (data == 'success') {
          // If the signup was successful, display a success message
          $('#signup-error').text('Sign up successful!');
        } else {
          // If the signup failed, display an error message
          $('#signup-error').text(data);
        }
      }
    });
  });
});
