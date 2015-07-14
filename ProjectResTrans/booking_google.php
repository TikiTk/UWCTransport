<?php
header("Access-Control-Allow-Origin: http://localhost:8100");
 if (isset($_POST['start_time'])) {
    session_start();
    require_once('./User.php');
    require_once('./Booking.php');
    $booking = new Booking();
    
    $start_time = trim($_POST['start_time']);
    $end_time = trim($_POST['end_time']);
    $depart = trim($_POST['depart']);
    $travel = trim($_POST['travel']);
    $booking_message = trim($_POST['message']);
    if(!$_SESSION['user_id']){
     $_SESSION['user_id'] = trim($_POST['user_id']);
    }
    if($booking_message == ''){
     $booking_message = 'no message';
    }
    //YY,mm,dd
    $booking_json = $booking->booking_transport($start_time, $end_time, $depart, $travel, $booking_message, $_SESSION['user_id']);

    //cancel booking.
    $booking_json_array = json_decode($booking_json, true);
    $booking->cancelBooking($booking_json_array["booking_id"]);
    //echo $booking_json;
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
        
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://script.google.com/macros/s/AKfycbx6oZCkVSQPDtrrOeiCmcsfAJ02OEnZp8o7CcVzEFfPi6avvc8/exec',
        CURLOPT_USERAGENT => 'Codular Sample cURL Request',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => json_decode($booking_json, true),
        CURLOPT_FOLLOWLOCATION => true

    ));


    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    
    //echo returned request.
    echo $resp;
    //echo $booking_json;
    // Close request to clear up some resources
    curl_close($curl);

 } else {
    echo '{"report": "Done"}';
 }
?>