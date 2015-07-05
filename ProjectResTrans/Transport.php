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
    
    public function assignTransportAttributes($transport_no){
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
             $this->stringLog .= "<br>Oops!: Could not execute query: sql. " .$this->mysqli->error." at assignTransportAttributes()";
        }
    }
     
    public function check_available($booking_start_time, $booking_end_time){//returns epoch time or returns  "available" as a string
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
        if($result = $this->mysqli->query($query)){
            if($result->num_rows > 0){
                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $DB_booking_start_time = $row['booking_start_time'];
                    $DB_booking_end_time = $row['booking_end_time'];
                    $DB_transport = $row['transport_no'];
                    
                    if(!isset($current_transport_no)){//checks a list of transport numbers from given type.
                        $current_transport_no = $row['transport_no'];
                    }else if($current_transport_no != $row['transport_no']){
                        if($get_next_transport_no){//getting the next transport number.
                            $current_transport_no = $row['transport_no'];
                            $get_next_transport_no = false;
                        }else{//found available transport number.
                            $this->transport_no = $current_transport_no;
                            $this->assignTransportAttributes($this->transport_no );
                            $this->stringLog .= "<br>Found available transport number: ".$this->transport_no;
                            return true;
                        }
                    }
                    
                    if(isset($DB_booking_start_time) && isset($DB_booking_end_time)){
                        if(!($booking_end_time < $DB_booking_start_time || $booking_start_time > $DB_booking_end_time)){//Case if specified time is outside the db time range or if there is no time allocated for db range.
                            $get_next_transport_no =true;
                            if(!isset($suggested_time_to_book)){//getting next time to book suggestion for user.
                                $suggested_time_to_book = $DB_booking_end_time;
                                //echo '<br>suggested_time_to_book: '.$suggested_time_to_book.'<br>';
                            }else if($suggested_time_to_book < $DB_booking_end_time){
                                $suggested_time_to_book = $DB_booking_end_time;
                            }
                        }
                    }    
                    
                }
                if(!$get_next_transport_no && $current_transport_no){//checks the last transport number.
                    $this->transport_no = $current_transport_no;
                    $this->assignTransportAttributes($this->transport_no );
                    $this->stringLog .= "<br>Found available transport number: ".$this->transport_no;
                    return true;
                }else{
                    //echo "Returning: ".$DB_booking_end_time;
                    return $suggested_time_to_book;
                }

            }else{
                
                $this->stringLog .= "<br>Nothing in list: ". $this->mysqli->affected_rows." at check_available(). ";
                return false;
            }
        }else{
            $this->stringLog .= "<br>Oops!: ". $this->mysqli->error." at check_available(). ";
        }
    }
}          