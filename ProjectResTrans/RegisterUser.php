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
        require_once('./Transport.php');
        
        $user = new User();
        $transport = new Transport();
        //$user->register_user("King", "Luu", "27764443100", "bnkwebana@gmail.com", "1", "student");
        //$user->register_user("Bongs", "Fee", "27761113100", "bdube83@gmail.com", "1", "student");
        //$user->register_user("Sizwe", "mbatha", "27732323223", "sizwembatha64@gmail.com", "1", "student");
        //$user->register_user("TK", "", "277000000000", "your working email", "your password", "student");
        
        /*
        $transport->register_transport("staff", "housingdriver1.myuwc@gmail.com", 1);
        $transport->register_transport("staff", "housingdriver2@gmail.com", 2);
        $transport->register_transport("staff", "housingdriver3@gmail.com", 3);
        $transport->register_transport("staff", "housingdriver4@gmail.com", 4);


        $transport->register_transport("student", "housingdriver1.myuwc@gmail.com", 11);
        $transport->register_transport("student", "housingdriver2@gmail.com", 22);
        $transport->register_transport("student", "housingdriver3@gmail.com", 33);
        $transport->register_transport("student", "housingdriver4@gmail.com", 44);
        
        $transport->register_transport("chc", "housingdriver1.myuwc@gmail.com", 111);
        $transport->register_transport("chc", "housingdriver2@gmail.com", 222);
        $transport->register_transport("chc", "housingdriver3@gmail.com", 333);
        $transport->register_transport("chc", "housingdriver4@gmail.com", 444);
        */
        
        echo $transport->stringLog;
        

        
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


