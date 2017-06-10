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

        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <?php
        session_start();
        session_destroy();
    ?>
        <script>
            jQuery(document).ready(function(){
                $( "#button_login" ).click(function( event ) {
                    
                     // Using JSONP to connect to login.php
                    $.ajax({
                        url: "https://uwctransport-bdube83.c9.io/ProjectResTrans/login.php",
                                
                           //prepering data to send.
                        type: 'POST',
                        data: $("#login_form").serialize(),
                        
                        //contentType: 'application/json; charset=utf-8',
                        
                        // Tell jQuery we're expecting JSON
                        dataType: "json", 
                        
                        // Work with the response
                        success: function( response_login ) {
                            console.log(response_login);
                            if(response_login.report == 'true'){
                                window.location.replace("https://uwctransport-bdube83.c9.io/ProjectResTrans/transport_booking.php");//cannot go back.
                                //window.location.href = "transport_booking.php"; //can go back.

                            }else{
                                alert('wrong password');
                            }
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
            <h1>Login</h1>
            </div>
        
            <div data-role="main" class="ui-content">
                <form id="login_form">
                    <div class="ui-field-contain">
                        <label for="email">E-mail:</label>
                        <input type="email" name="email" id="email" placeholder="Your email..">
                    </div>              
                    
                    <div class="ui-field-contain">
                        <label for="password">Password:</label>
                        <input type="password" name="pass" id="pass" placeholder="Your password..">
                    </div>
                    
                  <input type="button" id="button_login" data-inline="true" value="Submit">
                </form>
            </div>
        </div>
        
    </body>
</html>


