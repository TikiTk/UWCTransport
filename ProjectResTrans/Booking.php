<?php
require_once('User.php');
require_once('./Transport.php');
/**
 * Description of Booking
 *
 * @author uwcTransProjectTeam
 */
class Booking extends Transport {
    public $booking_id;
    public $booking_start_time;
    public $booking_end_time;
    public $booking_from;
    public $booking_to;
    public $booking_message;
    public $user_id;

    public function __construct() {
        parent::__construct();
    }
    public function __destruct() {
        //code
    }
    
    public function assignBookingAttributes($booking_id){
        $query = 'SELECT *'
                . ' FROM booking WHERE booking_id = "'.$booking_id.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->booking_id = $row['booking_id'];
            $this->booking_start_time = $row['booking_start_time']; 
            $this->booking_end_time =  $row['booking_end_time']; 
            $this->booking_from = $row['booking_from'];
            $this->booking_to = $row['booking_to'];
            $this->booking_message = $row['booking_message'];
            $this->user_id =  $row['user_id'];  
        }
        else {
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error." at assignBookingAttributes()";
        }
    }
    
    public function displayBooking($booking_id){
        $this->assignBookingAttributes($booking_id);
        //code
    }
    
    public function displayAllBookings() {
        $query ='SELECT *
                FROM booking
                ORDER BY booking_id DESC';
        $response = '';
        if($result = $this->mysqli->query($query)){
                if($result->num_rows > 0){
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                $this->booking_id = $row['booking_id'];
                                $this->booking_start_time = $row['booking_start_time'];
                                $this->booking_end_time = $row['booking_end_time'];
                                $this->user_id = $row['user_id'];
                                $response .= $this->toString();
                        }
                        $result->close();
                }
        }else {
            //Error
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error." at displayAllBookings()";
        }
        return trim($response);
        
    }
    
    public function booking_transport($booking_start_time, $booking_end_time, $booking_from, $booking_to, $booking_message, $userId) {
        $this->assignUserAttributes($userId);
        //checking for availbale trasport.
        $booking_start_timeA = strtotime($booking_start_time);
        $booking_end_timeA = strtotime($booking_end_time);
        $check_double_bookiung = $this->chek_double_booking($booking_start_timeA, $booking_end_timeA);
        if($check_double_bookiung == false){
            $booking_json = '{"report": "'.$this->stringLog.'"}';
            return $booking_json;
        }
        $check_available = $this->check_available($booking_start_timeA, $booking_end_timeA);
        if($check_available === true){
            $query = 'INSERT INTO booking(booking_start_time, booking_end_time, booking_from, booking_to,  booking_message, transport_id, user_id)'.
                                    ' VALUES (
                                    "'.$booking_start_timeA.'",
                                    "'.$booking_end_timeA.'",
                                    "'.$booking_from.'",
                                    "'.$booking_to.'",
                                    "'.$booking_message.'",
                                    "'.$this->transport_id.'",
                                    "'.$this->user_id.'") ';//insert user ID
                         
            if ($this->mysqli->query($query) === true){
                $this->stringLog .= "".$this->mysqli->affected_rows . " user updated.";
                if ($stmt = $this->mysqli->prepare('SELECT LAST_INSERT_ID()')) {//get $booking_id
                    $stmt->execute();
                    $stmt->bind_result($booking_id);
                    while ($stmt->fetch()) {
                    }
                        $stmt->close();
                }
                
                
                $booking_json = '{
                                    "booking_start_time": "'.trim($booking_start_time).'",'.
                                    '"booking_end_time": "'.trim($booking_end_time).'",'.
                                    '"booking_from": "'.trim($booking_from).'",'.
                                    '"booking_to": "'.trim($booking_to).'",'.
                                    '"booking_message": "'.trim($booking_message).'",'.
                                    '"transport_email": "'.trim($this->transport_email).'",'.
                                    '"user_number": "'.trim($this->user_number).'",'.
                                    '"booking_id": "'.trim($booking_id).'"}';
                return $booking_json;
                
            }
            else{
                $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error." at booking_transport()";
                $booking_json = '{"report": "'.$this->stringLog.'"}';
                return $booking_json;
            }
        }else if($check_available === false) {
            $this->stringLog .= "Transport is not available for specified date. Please check another time. ";
            $booking_json = '{"report": "'.$this->stringLog.'"}';
            return $booking_json;

        } else {
            $check_available = date('Y-m-d H:i:s', $check_available);
            $this->stringLog .= "Transport is not available for specified date. Please book after ".$check_available;
            $booking_json = '{"check_available": "'.$this->stringLog.'"}';
            return $booking_json;

        }
    }
    
    
    public function check_double_booking($booking_start_timeA, $booking_end_timeA){
        $query ='SELECT *
                FROM booking WHERE user_id='.$this->user_id.'
                ORDER BY booking_id DESC';
        $response = '';
        $booking_start_timeA = $booking_start_timeA+86000;
        if($result = $this->mysqli->query($query)){
                if($result->num_rows > 0){
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                $this->booking_id = $row['booking_id'];
                                $this->booking_start_time = $row['booking_start_time'];
                                $this->booking_end_time = $row['booking_end_time'];
                                $this->user_id = $row['user_id'];
                                if ($booking_start_timeA < $booking_end_time){
                                    $this->stringLog .= 'Please book after 24h or delete your current booking.';
                                    return false;
                                }
                        }
                        $result->close();
                }
        }else {
            //Error
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error." at displayAllBookings()";
        }
        return true;
    }
    
    public function cancelBooking($booking_id){
        $booking_id = $this->mysqli->real_escape_string($booking_id);
        $this->assignBookingAttributes($booking_id);
        $query = 'DELETE FROM booking WHERE booking_id = "'.$booking_id.'"';
        if ($this->mysqli->query($query) === true) {
                $this->stringLog .= "booking id: ".$booking_id. " has been removed. ";
        } else {
                $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error;
        }
    }
    
    public function toString(){
        //code
    }
}
