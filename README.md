# UWCTransport
Issues and TODOs:

•   Functionality:
    

    
    2   Cancel booking script method for google calender (When the user chooses to say no for an event).
    
    DONE:
        3   Fix 24hour format. Bug with Time picker for website. http://stackoverflow.com/questions/10891425/how-to-use-google-calendar-apis-events-insert-command-properly

        1   Still need to do the OAuth method for user registration.

        4   Still need to do fix checkSameDayBooking line 136 in Booking.php. #DoneDate 05/08
        
        5   Business logic for 24h booking.


•   User Interface: 

    
    8   Message about transport driver.
    
    
    10  Link to the lower version web app for unsupported browsers(IE).
    
    
    PAUSED:
        11   Still need to fix date and time for mobile app. #Paused.
    
    Combine Issue:
        "isseue 6" add to "issue 4":  User interface for same day booking. e.g "Please wait for 10min(google app trigger to check and remove past event) and try agian".
    DONE:
        7   TimeOut Function when screen loads and tries to connect to server "Please wait". .
        
        9   Prompt user If destination/departure is blank.
    
        5   Still need to do the fail.html notifications for login and booking errors
        
•   Meeting with RCS

        1   One driver who is the admin and driver(the admin will allocate the driver), more user friendly.
        
        2   @gmail.com staff(admin will verify if user is staff member).
        
        3   Staff bookkings higher priority.

        4   date month and day missmatch on google script.

•#please add issues or bugs.

Uwc transport programme
This is the application break down.
Please use university colours, it should be something simple but beautify and user friendly.
Create a web application for residential service transport. The application must accommodate both Students and staff members since they are using transport from one department. The application must allow booking and cancellations, send booking confirmation to student and staff. If the transport is fully booked it must send rejection message both staff and Student. The application must be flexible (allow admin to do changes in terms of time) since residential services use Students as their drivers and allows the admin to view bookings. Students and staff must receive a unique booking id. Lastly the must be able to keep the booking records.

There are four Actors in the system.
CHC STUDENT are the people voted by residence students they like SRC of residence so they have different privileges when they book for transport for example they can book a car with a driver to go to functions while normal residence students cannot.
NORMAL STUDENT, like me, can only book to transport their furniture from one UWC Residence to another and in emergency cases like hospital.
Stuff are the people working under residential and catering services (RCS) and they can book to drive a car so they don't need a driver if they have licence if not then they book like CHC members.
ADMIN: using google app script to view bookings and other system details from google drive.

Business Rules Draft:
1.	1 Res student can only make 1 booking.
2.	1 booking can be made by exactly 1 student.
3.	1 staff can only make 1 booking.

Software to be used:
•	GitHub
•		Used for team collaboration towards project.

•	MySQL or MangoDB (DataBase) or Microsoft SQL server ??
•		Used for storing data.

•	JSON Tutorial - W3Schools
•		Used for transporting data. Interoperable.

•	Google Apps Script
•		Send Email, Create Calendar Events, Admin database.

•	PHP 5 Tutorial
•		Used for server side coding.

•	CronJob
•		Used to schedule code execution at certain time intervals.


Website details

Website link: uwctransport.freeiz.com
To edit the website you will need FTP software, for example FileZila.
Changes first have to be done on our gitHub repository once verified by the team you can transfer the changes online.