<!DOCTYPE html>
<!--
-->
<html>
    <head>
        <!-- Include meta tag to ensure proper rendering and touch zooming -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Include jQuery Mobile stylesheets -->
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        
        <!-- Include the jQuery Mobile library -->
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        
        
        <link rel="stylesheet" type="text/css" href="datetime/jquery.datetimepicker.css"/ >
        <script src="datetime/jquery.js"></script>
        <script src="datetime/jquery.datetimepicker.js"></script>

        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            session_start();
            if (!isset($_SESSION['user_id'])) {
                header('Location: https://uwctransport-bdube83.c9.io/ProjectResTrans/transport_login.php');
            }
        ?>
        <script>

            jQuery(document).ready(function(){
                jQuery('#datetimepicker').datetimepicker();
                jQuery('#datetimepicker2').datetimepicker();
                
                $( "#signout" ).click(function( event ) {
                    window.location.replace('https://uwctransport-bdube83.c9.io/ProjectResTrans/transport_login.php');
                });
                
                $( "#button_book" ).click(function( event ) {
                    var querystring = "start_time="+$("#datetimepicker").val()+
                                        "&end_time="+$("#datetimepicker2").val()+
                                        "&depart="+$("#depart").val()+
                                        "&travel="+$("#travel").val()+
                                        "&message="+$("#message").val();
                     // Using JSONP to connect to booking_google.php
                    $.ajax({
                        url: "https://uwctransport-bdube83.c9.io/ProjectResTrans/booking_google.php",
                        
                        type: 'POST',
                        data: querystring,
                        
                        // Tell jQuery we're expecting JSON
                        dataType: "json",
                        
                        // Work with the response
                        success: function( response_booking ) {
                            console.log(response_booking);
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            console.log(error);
                            console.log(status);
                        }
                    });
                });
            });
              

            
        </script>
        
    
        <div data-role="page" data-theme="a">
            <div data-role="header">
            <h1>Transport Booking</h1>
            </div>
        
            <div data-role="navbar">
            	<ul>
            		<li><a id="signout" >Sign-out</a></li>
            	</ul>
            </div><!-- /navbar -->
            
            <div data-role="main" class="ui-content">
                <form id="book">
                    <div class="ui-field-contain">
                        <label for="start_time">Booking start time:</label>
                        <input id="datetimepicker"  name="start_time"  placeholder="click to enter start date.." type="text" >
                    </div>
                    
                    <div class="ui-field-contain">
                        <label for="end_time">Booking end time:</label>
                        <input id="datetimepicker2"  name="end_time" placeholder="click to enter end date.."  type="text" >
                    </div>
                    
                    <div class="ui-field-contain">
                        <label for="depart">Depart from:</label>
                        <select name="depart" id="depart">
                            <option value="Main_Campus">Main Campus</option>
                            <option value="Gorvalla">Gorvalla</option>
                            <option value="Disa">Disa</option>
                        </select>
                    </div>

                    <div class="ui-field-contain">
                        <label for="travel">Travel to:</label>
                        <select name="travel" id="travel">
                            <option value="Main_Campus">Main Campus</option>
                            <option value="Gorvalla">Gorvalla</option>
                            <option value="Disa">Disa</option>
                        </select>
                    </div>

                    <label for="message">Message:</label>
                    <input type="text" name="message" id="message" placeholder="Message for the driver.. e.g 'I'll be waiting by the ATM..'">
                    
                  <input type="button" id="button_book" data-inline="true" value="Submit">
                </form>
            </div>
        </div>
        
    </body>
</html>