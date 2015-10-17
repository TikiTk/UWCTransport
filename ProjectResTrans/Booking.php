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
        $booking_start_timeEP = strtotime($booking_start_time);
        $booking_end_timeEP = strtotime($booking_end_time);
        $checkSameDayBooking = $this->checkSameDayBooking($booking_start_timeEP, $booking_end_timeEP);
        if($checkSameDayBooking == false){
            $booking_json = '{"report": "'.$this->stringLog.'"}';
            return $booking_json;
        }
        $check_available = $this->check_available($booking_start_timeEP, $booking_end_timeEP);
        if($check_available === true){
            $query = 'INSERT INTO booking(booking_start_time, booking_end_time, booking_from, booking_to,  booking_message, transport_id, user_id)'.
                                    ' VALUES (
                                    "'.$booking_start_timeEP.'",
                                    "'.$booking_end_timeEP.'",
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
                $booking_start_time = date("Y-m-d\TH:i", strtotime(trim($booking_start_time)));
                $booking_end_time = date("Y-m-d\TH:i", strtotime(trim($booking_end_time)));
                $booking_json = '{
                                    "booking_start_time": "'.trim($booking_start_time).'",'.
                                    '"booking_end_time": "'.trim($booking_end_time).'",'.
                                    '"booking_from": "'.trim($booking_from).'",'.
                                    '"booking_to": "'.trim($booking_to).'",'.
                                    '"booking_message": "'.trim($booking_message).'",'.
                                    '"transport_email": "'.trim($this->transport_email).'",'.
                                    '"user_type": "'.trim($this->user_type).'",'.
                                    '"user_fname": "'.trim($this->user_fname).'",'.     
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
    
    public function booking_json($booking_start_time, $booking_end_time, $booking_from, $booking_to, $booking_message, $userId) {
        $this->assignUserAttributes($userId);
        //checking for availbale trasport.
        $booking_start_timeEP = strtotime($booking_start_time);
        $booking_end_timeEP = strtotime($booking_end_time);
        $checkSameDayBooking = $this->checkSameDayBooking($booking_start_timeEP, $booking_end_timeEP);
        if($checkSameDayBooking == false){
            $booking_json = '{"report": "'.$this->stringLog.'"}';
            return $booking_json;
        }
        $check_available = $this->check_available($booking_start_timeEP, $booking_end_timeEP);
        if($check_available === true){
            $booking_start_time = date("Y-m-d\TH:i", strtotime(trim($booking_start_time)));
            $booking_end_time = date("Y-m-d\TH:i", strtotime(trim($booking_end_time)));
            $booking_json = '{
                                "booking_start_time": "'.trim($booking_start_time).'",'.
                                '"booking_end_time": "'.trim($booking_end_time).'",'.
                                '"booking_from": "'.trim($booking_from).'",'.
                                '"booking_to": "'.trim($booking_to).'",'.
                                '"booking_message": "'.trim($booking_message).'",'.
                                '"transport_email": "'.trim($this->transport_email).'",'.
                                '"user_type": "'.trim($this->user_type).'",'.
                                '"user_fname": "'.trim($this->user_fname).'",'.
                                '"user_number": "'.trim($this->user_number).'",'.
                                '"booking_id": "'.trim('null').'"}';
            return $booking_json;
                
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
    
    //TODO: To avoid users double booking or booking on the same day. line 77
    public function checkSameDayBooking($booking_start_timeEP, $booking_end_timeEP){
        $query ='SELECT *
                FROM booking WHERE user_id='.$this->user_id.'
                ORDER BY booking_id DESC';
        $response = '';
        //$booking_start_timeEP = strtotime('-1 day', $booking_start_timeEP);
        if($result = $this->mysqli->query($query)){
                if($result->num_rows > 0){
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){

                                if ($booking_start_timeEP < $row['booking_end_time']){
                                    $this->stringLog .= 'Sorry you cannot book on the same day. Please book 24h after '. date('Y-m-d H:i:s', $row['booking_end_time']).' or delete your current booking.';
                                    //$this->stringLog .= 'BoolkedE: p'. $row['booking_end_time'].' or delete your current booking.';
                                    //$this->stringLog .= 'Booking Date: '. date('Y-m-d H:i:s', $booking_start_timeEP);
                                    //$this->stringLog .= 'Booking DateEp: '. $booking_start_timeEP.' or delete your current booking.';
                                    return false;
                                }
                        }
                        $result->close();
                }
        } else {
            //Error
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error." at displayAllBookings()";
            return false;
        }
        return true;
    }
    public function clearTransportDateTable() {
        $query = 'DELETE FROM transport_date';
        if ($this->mysqli->query($query) === true) {
            $this->stringLog .= "All the data in 'transport_date' has been removed. ";
        } else {
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error;
        }
    }
    
    public function findDriverId($start_time, $end_time, $transport_email){
        $transport_email = htmlentities($this->mysqli->real_escape_string($transport_email));

        $query = 'SELECT * '
                . ' FROM transport WHERE transport_email= "'.$transport_email.'"';
                
        if ($result = $this->mysqli->query($query)) {
            
            $row = $result->fetch_assoc();
            $transport_id =  $row['transport_id']; 
            if($transport_id){
                return $transport_id;
            }else{
                $this->stringLog .= "The driver with email ".$transport_email." does not exists.";
            }

        }
        else {
            //somthing went wrong.
           $this->stringLog .= "Oops!: Could not execute query: sql. " .$this->mysqli->error;
        }
        return null;
    }
    
    public function insertDriver($driverTimeTable) {
        $this->clearTransportDateTable();
        foreach ($driverTimeTable as $driver) {
            if ($driver['start_time'] && $driver['end_time'] && $driver['name']) {
                $this->insertDriverHelper($driver['start_time'], $driver['end_time'], $driver['name']);
            }
        }
    }
    
    public function insertDriverHelper($start_time, $end_time, $transport_email) {
        $driverId = $this->findDriverId($start_time, $end_time, $transport_email);
        $start_timeEP = strtotime($start_time);
        $end_timeEP = strtotime($end_time);
        
        if($driverId) {
            $query = 'INSERT INTO transport_date(start_time, end_time, transport_id)'.
            ' VALUES (
            "'.$start_timeEP.'",
            "'.$end_timeEP.'",
            "'.$driverId.'") ';
            if ($this->mysqli->query($query) === true) {
                $this->assignUserEmailAttributes($user_number);
                $this->stringLog .= "".$this->mysqli->affected_rows . " trasport date updated.";
            } else {
                $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error;
            }
        }
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
