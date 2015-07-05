<!DOCTYPE html>
<!--
-->
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <script>
              
             // Using JSONP
            /*$.ajax({
                url: "https://script.google.com/macros/s/AKfycbzo_EGanCsMh2j8rjdtlVPjtgBYgf2QR8KLKHnJ8PHIfxjphQmM/exec",
             
                // The name of the callback parameter, as specified by the YQL service
                jsonp: "cb",
                
                // Tell jQuery we're expecting JSONP
                dataType: "jsonp",
             
    
             
                // Work with the response
                success: function( response ) {
                      alert(response.testing);
                },
                
                error: function (request, status, error) {
                    console.log(request.responseText);
                    console.log(error);
                    console.log(status);
                }
                
            });*/
            
            
        </script>
        <?php
        require_once('./User.php');
        require_once('./Booking.php');
        
        $user = new User();
        $user->register_user("King", "Luu", "27764443100", "bnkwebana@gmail.com ", "1", "student");
        $user->register_user("Bongs", "Fee", "27761113100", "bdube83@gmail.com ", "1", "student");
        echo $user->stringLog;

        
        //$booking = new Booking();
        //$booking->login_user('3239', '1');
        //$booking->booking_transport(strtotime('12/02/15 15:50'), strtotime('12/02/15 16:50'), "Disa", "Gorv");
        //$booking->cancelBooking(2);
       // echo $booking->stringLog;
        // put your code here
        ?>
    
      <div id="inner">Hello</div>
      </body>
</html>


