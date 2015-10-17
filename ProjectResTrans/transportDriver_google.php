<?php
header("Access-Control-Allow-Origin: http://localhost:8100");
   session_start();
   require_once('./User.php');
   require_once('./Booking.php');
   $booking = new Booking();
   
   /* Message to send to google.*/
   $booking_json = '{ "state" : "start"}';
   
   $curl = curl_init();
   
   /* Set some options.*/
   curl_setopt_array($curl, array(
       CURLOPT_RETURNTRANSFER => 1,
       CURLOPT_URL => 'https://script.google.com/macros/s/AKfycbxKoEhAeol-0ssO_OiEq73pNBU0vkxSCqMkNNCylTyACZMPN1K1/exec',
       CURLOPT_USERAGENT => 'Codular Sample cURL Request',
       CURLOPT_POST => 1,
       CURLOPT_POSTFIELDS => json_decode($booking_json, true),
       CURLOPT_FOLLOWLOCATION => true
   ));

   /* Send the request & save response to $resp.*/
   $resp = curl_exec($curl);
   
   $driverTimeTable =  json_decode($resp, true);
   

   $booking->insertDriver($driverTimeTable);
   
   echo $booking->stringLog;
   /* Print returned request.*/
   echo ($resp);
   
   /* Close request to clear up some resources.*/
   curl_close($curl);
?>