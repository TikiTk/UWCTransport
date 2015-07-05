<?php
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
    if($booking_message == ''){
     $booking_message = 'no message';
    }
    //YY,mm,dd
    $booking_json = $booking->booking_transport($start_time, $end_time, $depart, $travel, $booking_message, $_SESSION['user_id']);

    //cancel booking.
    //$booking_json_array = json_decode($booking_json, true);
    //$booking->cancelBooking($booking_json_array["booking_id"]);
    
    echo $booking_json;
   //echo '{"report": "'.strtotime($start_time).'"}';
 }
?>