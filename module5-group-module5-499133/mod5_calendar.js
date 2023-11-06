// Define currentMonth and events variables
var currentMonth = new Month(new Date().getFullYear(), new Date().getMonth());
var events = [];

// Function to update calendar with new month and year
function updateCalendar(month, year) {
// Update currentMonth variable
currentMonth = new Month(year, month);

// Get weeks for current month
var weeks = currentMonth.getWeeks();

// Define variables for calendar data and event dates
var calendarData = [];
var eventDates = [];

// AJAX call to retrieve event dates
$.ajax({
type: 'GET',
url: 'mod5_eventdates.php',
async: false,
success: function(data) {
eventDates = JSON.parse(data);
}
});

// Loop through weeks and days to create calendar data
for (var i = 0; i < weeks.length; i++) {
var week = weeks[i];
var weekData = [];
for (var j = 0; j < 7; j++) {
var dateObj = week.getDates()[j];
var dateStr = dateObj.toISOString().split('T')[0];
if (dateObj.getMonth() !== month) {
weekData.push('<td class="other-month" style="pointer-events: none; opacity: 0.5;">' + dateObj.getDate() + '</td>');
} else if (eventDates.indexOf(dateStr) !== -1) {
weekData.push('<td class="has-event" style="border: 2px solid blue;">' + dateObj.getDate() + '</td>');
} else {
weekData.push('<td>' + dateObj.getDate() + '</td>');
}
}
calendarData.push(weekData);
}

// Create table HTML from calendar data
var monthName = currentMonth.getDateObject(1).toLocaleString('default', { month: 'long' });
var response = { month: monthName + ' ' + year, calendarData: calendarData };
var table = '<table id="calendar-table"><thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead><tbody>';
for (var i = 0; i < response.calendarData.length; i++) {
table += '<tr>';
for (var j = 0; j < response.calendarData[i].length; j++) {
table += response.calendarData[i][j];
}
table += '</tr>';
}
table += '</tbody></table>';

// Insert HTML into calendar container and update month label
$('#calendar-container').html(table);
$('#calendar-month').text(response.month);
}

// Document ready function to initialize calendar and add event listeners
$(function() {
// Initialize calendar with current month and year
var currentMonth = new Month(new Date().getFullYear(), new Date().getMonth());
updateCalendar(currentMonth.month, currentMonth.year);

    $('#prev-month').click(function() {
        currentMonth = currentMonth.prevMonth();
        updateCalendar(currentMonth.month, currentMonth.year);
    });

    $('#next-month').click(function() {
        currentMonth = currentMonth.nextMonth();
        updateCalendar(currentMonth.month, currentMonth.year);
    });
});


$(function() {
    $('#add-event').on('click', function() {

      console.log('Add event button clicked');
    });
  
    $('#view-edit-event').on('click', function() {

      console.log('View/Edit event button clicked');
    });
  });
  

$(function () {
    var selectedDate;
  
    $(document).on("click", "#calendar-table td", function () {
      $("#calendar-table td").removeClass("selected");
      $(this).addClass("selected");
      selectedDate = $(this).text();
    });
  
    $('#add-event').on('click', function () {
      if (selectedDate) {
        var eventForm = ` 
        <div id="event-form"> 
          <label>Title:</label> 
          <input type="text" id="event-title" name="title"><br> 
          <label>Date:</label> 
          <input type="text" id="event-date" name="date" value="${currentMonth.getDateObject(1).toLocaleString('en-US', { month: 'long' })} ${selectedDate} ${currentMonth.year}"> 
          <label>Time(HH:MM:SS):</label> 
          <input type="text" id="event-time" name="time"><br> 
          <input type="submit" value="Register" id="register-event"> 
          <input type="button" value="Close" id="close-event-form"> 
        </div>`;

        $('body').append(eventForm);
      } else {
        alert('Please select a date on the calendar.');
      }
    });

    $('#view-edit-event').on('click', function() {
      if (selectedDate) {
          $.ajax({
              type: 'POST',
              url: 'mod5_eventview.php',
              data: {
                  date: currentMonth.year + '-' + (currentMonth.month + 1).toString().padStart(2, '0') + '-' + selectedDate.toString().padStart(2, '0')
              },
              success: function(data) {
                events = JSON.parse(data);
                  if (events.length > 0) {
                      var eventDetails = '<div class="event-details">';
                      events.sort(function(a, b) { return new Date(a.event_date + 'T' + a.event_time) - new Date(b.event_date + 'T' + b.event_time); });
                      for (var i = 0; i < events.length; i++) {
                        eventDetails += 'Title: ' + events[i].title + ', ';
                        eventDetails += 'Date: ' + events[i].event_date + ', ';
                        eventDetails += 'Time: ' + events[i].event_time + '  ';
                        eventDetails += '<button class="edit-event" data-index="' + i + '">Edit</button>';
                        eventDetails += '<button class="delete-event" data-index="' + i + '">Delete</button>';

                        if (i < events.length - 1) {
                          eventDetails += '<hr>';
                        }
                      }                      
                      eventDetails += '</div>';
                      $('#event-details-container').html(eventDetails);
                  } else {
                      $('#event-details-container').html('<p>No events found for this date</p>');
                  }
              }
          });
      } else {
          alert('Please select a date on the calendar.');
      }
  });
  
    $(document).on('click', '#close-event-form', function () {
      $('#event-form').remove();
    });
    
    
  
    $(document).on('click', '#register-event', function (e) {
      e.preventDefault();
      var title = $('#event-title').val();
      var date = currentMonth.year + '-' + (currentMonth.month + 1).toString().padStart(2, '0') + '-' + selectedDate.toString().padStart(2, '0');
      var time = $('#event-time').val();
      $.ajax({
          type: 'POST',
          url: 'mod5_eventadd.php',
          data: { title: title, date: date, time: time },
          success: function (data) {
              if (data == 'success') {
                  $('#event-form').remove();
                  alert('Event registered successfully!');
                  updateCalendar(currentMonth.month, currentMonth.year);
                  $('#view-edit-event').trigger('click'); 
              } else {
                  alert('Error registering event: ' + data);
              }
          }
      });
  });

  $(document).on('click', '.delete-event', function () {
    var index = $(this).data('index');
    var eventToDelete = events[index];
    $.ajax({
        type: 'POST',
        url: 'mod5_eventdelete.php',
        data: {
            event_title: eventToDelete.title,
            event_date: eventToDelete.event_date,
            event_time: eventToDelete.event_time,
        },
        success: function (data) {
            if (data == 'success') {
                alert('Event deleted successfully!');
                updateCalendar(currentMonth.month, currentMonth.year);
                $('#view-edit-event').trigger('click'); 
            } else {
                alert('Error deleting event: ' + data);
            }
        },
    });
});
});

  $(document).on('click', '.edit-event', function () {
    var index = $(this).data('index');
    var eventToEdit = events[index];

    $(this).parent().html(`
        <div class="edit-form">
            <label>Title:</label>
            <input type="text" class="edit-title" value="${eventToEdit.title}">
            <label>Date:</label>
            <input type="date" class="edit-date" value="${eventToEdit.event_date}">
            <label>Time:</label>
            <input type="text" class="edit-time" value="${eventToEdit.event_time}">
            <button class="save-event" data-index="${index}">Save</button>
            <button class="close-edit" data-index="${index}">Close</button>
        </div>
    `);
});

$(document).on('click', '.close-edit', function () {
    var index = $(this).data('index');
    var eventToClose = events[index];

    $(this).parent().html(`
        Title: ${eventToClose.title}, Date: ${eventToClose.event_date}, Time: ${eventToClose.event_time}
        <button class="edit-event" data-index="${index}">Edit</button>
        <button class="delete-event" data-index="${index}">Delete</button>
    `);
    $('#view-edit-event').trigger('click');
});

$(document).on('click', '.save-event', function () {
    var index = $(this).data('index');
    var eventToUpdate = events[index];

    var newTitle = $(this).siblings('.edit-title').val();
    var newDate = $(this).siblings('.edit-date').val();
    var newTime = $(this).siblings('.edit-time').val();

    console.log("eventToUpdate.title: " + eventToUpdate.title);
    console.log("eventToUpdate.event_date: " + eventToUpdate.event_date);
    console.log("eventToUpdate.event_time: " + eventToUpdate.event_time);
    console.log("new_title: " + newTitle);
    console.log("newDate: " + newDate);
    console.log("newTime: " + newTime);

    $.ajax({
        type: 'POST',
        url: 'mod5_eventupdate.php',
        data: {
            old_title: eventToUpdate.title,
            old_date: eventToUpdate.event_date,
            old_time: eventToUpdate.event_time,
            new_title: newTitle,
            new_date: newDate,
            new_time: newTime
        },
        success: function (data) {
            if (data == 'success') {
                alert('Event updated successfully!');
                updateCalendar(currentMonth.month, currentMonth.year);
                $('#view-edit-event').trigger('click');
            } else {
                alert('Error updating event: ' + data);
            }
        }
    });
});

  