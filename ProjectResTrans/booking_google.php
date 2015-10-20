<?php
header("Access-Control-Allow-Origin: http://localhost:8100");
 if (isset($_POST['start_time'])) {
    session_start();
    require_once('./User.php');
    require_once('./Booking.php');
    $booking = new Booking();

    $start_time = date("Y/m/d H:i", strtotime(trim($_POST['start_time'])));
    $end_time = date("Y/m/d H:i", strtotime(trim($_POST['end_time'])));
    //echo "start time: ".$start_time." end time: ".$end_time;
    //return;
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
    $booking_json = $booking->booking_json($start_time, $end_time, $depart, $travel, $booking_message, $_SESSION['user_id']);

    //cancel booking.
    $booking_json_array = json_decode($booking_json, true);
    //$booking->cancelBooking($booking_json_array["booking_id"]);
    //echo $booking_json;
    // Get cURL resource
    //print_r($booking_json_array);
    if(!$booking_json_array['check_available'] && !$booking_json_array['report']){
     
     $booking_json = $booking->booking_transport($start_time, $end_time, $depart, $travel, $booking_message, $_SESSION['user_id']);
     $booking_json_array = json_decode($booking_json, true);
     
     $curl = curl_init();
     
     /* Set some options.*/
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
     
     //print returned request.
     //echo $booking_json;
     //echo $resp;
     echo $resp;
     $resp_array = json_decode($resp, true);
     
     //after checking if transport is available with booking_json and sending booking to google then booking_transport insert booking.
     if($resp_array['testing'] == "success"){
     } else {
       $booking->cancelBooking($booking_json_array['booking_id']);
     }
     // Close request to clear up some resources
     curl_close($curl);
    } else {
       echo $booking_json;
    }
 } else {
    echo '{"report": "Done"}';
 }
?>