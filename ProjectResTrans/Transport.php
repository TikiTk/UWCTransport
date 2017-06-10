<?php

/*
 * Project 2015.
 */

/**
 * Description of Transport
 *
 * @author uwcTransProjectTeam
 */
class Transport extends User {
    public $transport_id;
    public $transport_no;
    public $transport_type;
    public $transport_email;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function __destruct() {
        $this->mysqli->close();    
    }
    
    public function assignTransportAttributes($transport_no) {
        $query = 'SELECT *'
                . ' FROM transport WHERE transport_no = "'.$transport_no.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->transport_id = $row['transport_id'];
            $this->transport_type = $row['transport_type'];
            $this->transport_email = $row['transport_email'];
            $this->transport_no = $transport_no;
        }
        else {
             $this->stringLog .= "Oops!: Could not execute query: sql. " .$this->mysqli->error." at assignTransportAttributes()";
        }
    }
     
    public function check_available($booking_start_time, $booking_end_time) {//returns epoch time or returns  "available" as a string.
        $get_next_transport_no = false;
        $query = 'SELECT * FROM transport '//combining transport and booking.
                . 'LEFT JOIN booking '
                . 'ON booking.transport_id=transport.transport_id '
                . 'WHERE transport.transport_type = "'.$this->user_type.'" '
                . 'UNION '
                . 'SELECT * FROM transport '
                . 'RIGHT JOIN booking '
                . 'ON booking.transport_id=transport.transport_id '
                . 'WHERE transport.transport_type = "'.$this->user_type.'" '
                . 'ORDER BY transport_no ASC';
        if($result = $this->mysqli->query($query)) {
            if($result->num_rows > 0) {
                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $DB_booking_start_time = $row['booking_start_time'];
                    $DB_booking_end_time = $row['booking_end_time'];
                    $DB_transport = $row['transport_no'];
                    
                    if(!isset($current_transport_no)) {//checks a list of transport numbers from given type.
                        $current_transport_no = $row['transport_no'];
                    }else if($current_transport_no != $row['transport_no']) {
                        if($get_next_transport_no) {//getting the next transport number.
                            $current_transport_no = $row['transport_no'];
                            $get_next_transport_no = false;
                        }else {//found available transport number.
                            $this->transport_no = $DB_transport;
                            $this->assignTransportAttributes($this->transport_no);
                            $check_driver_available = $this->checkAvailableTimeTable($booking_start_time, $booking_end_time, $this->transport_id);
                            if($check_driver_available === true) {
                                $this->stringLog .= "Found available transport number: ".$this->transport_no;
                                return true;
                            }
                        }
                    }
                    
                    if(isset($DB_booking_start_time) && isset($DB_booking_end_time)) {
                        if(!($booking_end_time < $DB_booking_start_time || $booking_start_time > $DB_booking_end_time)) {//Case if specified time is outside the db time range or if there is no time allocated for db range.
                            $get_next_transport_no =true;
                            if(!isset($suggested_time_to_book)){//getting next time to book suggestion for user.
                                $suggested_time_to_book = $DB_booking_end_time;
                                //echo 'suggested_time_to_book: '.$suggested_time_to_book.'';
                            }else if($suggested_time_to_book < $DB_booking_end_time) {
                                $suggested_time_to_book = $DB_booking_end_time;
                            }
                        }
                    }    
                    
                }
                
                if(!$get_next_transport_no && $current_transport_no){//checks the last transport number.
                    $this->transport_no = $current_transport_no;
                    $this->assignTransportAttributes($this->transport_no);
                    $check_driver_available = $this->checkAvailableTimeTable($booking_start_time, $booking_end_time, $this->transport_id);
                    if($check_driver_available === true) {
                        $this->stringLog .= "Found available transport number: ".$this->transport_no;
                        return true;
                    }else if($check_driver_available === false) {
                        $this->stringLog .= "From driver's in time table: ";
                        $booking_json = '{"report": "'.$this->stringLog.'"}';
                        return false;
                    } else {
                        $this->stringLog .= "Something went wrong while looking for a driver. Please try again later. ";
                        $booking_json = '{"report": "'.$this->stringLog.'"}';
                        return false; 
                    }                
                }else {
                    //echo "Returning: ".$DB_booking_end_time;
                    return $suggested_time_to_book;
                }

            } else {
                if($this->user_type) {
                    $this->stringLog .= "Nothing in list for user type: ". $this->user_type.". ";
                }else {
                    $this->stringLog .= "User is not signed in.";
                }
                return false;
            }
        }else {
            $this->stringLog .= "Oops!: ". $this->mysqli->error." at check_available(). ";
        }
    }
    
    public function checkAvailableTimeTable($booking_start_time, $booking_end_time, $transport_id) {
        $isAvailable = false;
        $query = 'SELECT *'
        . ' FROM transport_date WHERE transport_id = "'.$transport_id.'"';
        
       if($result = $this->mysqli->query($query)) {
        if($result->num_rows > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $DB_start_time = $row['start_time'];
                $DB_end_time = $row['end_time'];
                
                if(($booking_start_time >= $DB_start_time) && ($booking_end_time <= $DB_end_time)) {
                    $isAvailable = true;
                }
                
            }
        } else {
            //$this->stringLog .= "Nothing in list for transport id: ".$transport_id." at checkAvailableTimeTable(). ";
            $stringLog = "Nothing in list for transport id: ".$transport_id." at checkAvailableTimeTable(). ";
        }
       }
       return $isAvailable;
    }
    
     public function register_transport($transport_type, $transport_email, $transport_no){
        $transport_type = htmlentities($this->mysqli->real_escape_string($transport_type));
        $transport_email = htmlentities($this->mysqli->real_escape_string($transport_email));
        $transport_no = htmlentities($this->mysqli->real_escape_string($transport_no));
        $query = 'SELECT * '
                . ' FROM transport WHERE transport_no= "'.$transport_no.'"';
        if ($result = $this->mysqli->query($query)) {
            if ($result->num_rows > 0) {
                $this->stringLog .= "The transport_no ".$transport_no." already exists.";
                return;
            }
        }
        $query = 'INSERT INTO transport(transport_type, transport_email, transport_no)'.//yes you right. because its going cause problems
        ' VALUES (
        "'.$transport_type.'",
        "'.$transport_email.'",
        "'.$transport_no.'") ';
        if ($this->mysqli->query($query) === true) {
            $this->stringLog .= "".$this->mysqli->affected_rows . " tranpsort updated.";
            //login Go to Home page.
        } else {
            //transport exist
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error;
        }
    }
    
}          