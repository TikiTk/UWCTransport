var logstr = "";
function doPost(e) {
  try{
  var booking_start_time = e.parameter.booking_start_time+":00";
  var booking_end_time = e.parameter.booking_end_time+":00";
  var booking_from =  e.parameter.booking_from;
  var booking_to =  e.parameter.booking_to;
  var user_email =  e.parameter.user_number;
  var transport_email = e.parameter.transport_email;
  var description = e.parameter.booking_message;
  var user_type = e.parameter.user_type;
  var user_fname = e.parameter.user_fname;
  var summary = "Transport Booking.";
  
  var event = createEvent(summary,  booking_from, booking_to, description, booking_start_time, booking_end_time, user_email, transport_email);
  writeOnSpreadSheer(event, user_type, user_fname);
  sendEmails2(event, user_type);
  var result = {
    testing:  "success",
    eventID: event
  };
  //var cb = e.parameter.cb;
  var outputStr = JSON.stringify(result);
    log(logstr+"\n\nDone with everything on: "+new Date());
  }catch(e){
    log(e+" on " + new Date());
  }
  return ContentService.createTextOutput(outputStr).setMimeType(ContentService.MimeType.JSON);
}

function createEvent(summary,  booking_from, booking_to, description, booking_start_time, booking_end_time, user_email, transport_email) {
  try{
  var calendarId = 'primary';
  var event = {
    summary: summary,
    location: 'From ' + booking_from + ' to ' + booking_to,
    description: description,
    start: {
      dateTime: booking_start_time,
      timeZone: "UTC+02:0"
    },
    end: {
      dateTime: booking_end_time,
      timeZone: "UTC+02:00"
    },
    attendees: [
      {email: transport_email},
      {email: user_email}
    ],
    // Red background. Use Calendar.Colors.get() for the full list.
   colorId: 11
   };

  var optionalArgs = {
    sendNotifications: true
  };

  event = Calendar.Events.insert(event, calendarId, optionalArgs);
  Logger.log('Event ID: ' +event.id);
  logstr += "\nDone creating the event "+ new Date();
  }catch(e) {
    log(e+" on " + new Date());
  }

  return event;
}

function writeOnSpreadSheer(events, user_type, user_fname){
  try{
    var ss = SpreadsheetApp.openById("1nsp66Vhww95aQol2YkKhy3CSSZUMhzwFIShxDHHZvAY");
    var details=[[events.attendees[0].email, events.attendees[1].email, user_fname, events.start.dateTime, events.end.dateTime, events.location, '', user_type]];
    var sheet = ss.getSheets()[0];
    var range=sheet.getRange(sheet.getLastRow()+1,1,1,8);
    range.setValues(details);
    logstr += "\nDone writing on spreadsheet "+ new Date();
  }catch(e){
    log(e+" on " + new Date());
  }
}


// This constant is written in column C for rows for which an email
// has been sent successfully.
var EMAIL_SENT = "EMAIL_SENT";

function sendEmails2(event, user_type) {
  try{
    var ss = SpreadsheetApp.openById("1nsp66Vhww95aQol2YkKhy3CSSZUMhzwFIShxDHHZvAY");
    var sslink = "https://docs.google.com/spreadsheets/d/1nsp66Vhww95aQol2YkKhy3CSSZUMhzwFIShxDHHZvAY/edit#gid=0";
    var sheet = ss.getSheets()[0];
    var startRow = 2;  // First row of data to process
    var numRows = 1;   // Number of rows to process
    // Fetch the range of cells A2:B3
    var dataRange = sheet.getRange(sheet.getLastRow(), 1, 1, 6)
    var data = dataRange.getValues();
    var emailAddress = "bdube83@gmail.com";  // ADMIN EMAIL
    //var emailAddress2 = data[0][1];  // Second column
    var emailSent = data[6];     // Sixth column
    if (emailSent != EMAIL_SENT) {  // Prevents sending duplicates
      var subject = "RCSTransport Booking Event";
      MailApp.sendEmail(emailAddress, subject, user_type+" user event Link:\n\n "+event.htmlLink+"\n\n Spreadsheet Link:\n"+sslink);
      //MailApp.sendEmail(emailAddress2, subject, event.htmlLink);
      sheet.getRange(sheet.getLastRow(), 7).setValue(EMAIL_SENT);
      // Make sure the cell is updated right away in case the script is interrupted
      SpreadsheetApp.flush();
    }
    logstr += "\nDone sending email to admin on spreadsheet "+ new Date();
  }catch(e){
    log(e+" on " + new Date());
  }
}


function log(logString){
  var ss = SpreadsheetApp.openById("1nsp66Vhww95aQol2YkKhy3CSSZUMhzwFIShxDHHZvAY");
  var details=[[logString]];
  var sheet = ss.getSheets()[1];
  var range=sheet.getRange(sheet.getLastRow()+1,1,1,1);
  range.setValues(details);
}


