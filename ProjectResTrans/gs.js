function doGet(e) {
  var booking_start_time = e.parameter.booking_start_time;
  var booking_end_time = e.parameter.booking_end_time;
  var booking_from =  e.parameter.booking_from;
  var booking_to =  e.parameter.booking_to;
  var user_email = 'bdube83@gmail.com';
  var transport_email = 'bdube83@yahoo.com';
  var summary = "Transport Booking.";
  var description = "Please accept the invitation before the booking deadline.";
  
  var eventID = createEvent(summary,  booking_from, booking_to, description, booking_start_time, booking_end_time, user_email, transport_email);
  var result = {
    testing:  "success",
    eventID: eventID
  };
  var cb = e.parameter.cb;
  var outputStr = cb + '(' + JSON.stringify(result) + ');'
  return ContentService.createTextOutput(outputStr).setMimeType(ContentService.MimeType.JAVASCRIPT);
  //so from now on we'll be coding here
}

function createEvent(summary,  booking_from, booking_to, description, booking_start_time, booking_end_time, user_email, transport_email) {
  
  var calendarId = 'primary';
  var event = {
    summary: summary,
    location: 'from ' + booking_from + ' to ' + booking_to,
    description: description,
    start: {
      dateTime: (new Date(booking_start_time)).toISOString()
    },
    end: {
      dateTime: (new Date(booking_end_time)).toISOString()
    },
    attendees: [
      {email: user_email},
      {email: transport_email}
    ],
    // Red background. Use Calendar.Colors.get() for the full list.
   colorId: 11
   };

  var optionalArgs = {
    sendNotifications: true
  };

  event = Calendar.Events.insert(event, calendarId, optionalArgs);
  Logger.log('Event ID: ' +event.id);

  return event;
}
