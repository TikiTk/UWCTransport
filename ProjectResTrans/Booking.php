<?php
require_once('./User.php');
require_once('./Transport.php');
/**
 * Description of Booking
 *
 * @author uwcTransProjectTeam
 */
class Booking extends User {
    public $book_num;
    public $book_starting_time;
    public $book_ending_time;
    public $book_date;
    public $user_id;
    public $trans_no;
    
    public function __construct() {
        parent::__construct();
    }
    public function __destruct() {
        //code
    }
    
    public function assignBookingAttributes($book_num){
        $query = 'SELECT '
                . ' FROM booking WHERE book_num = "'.$book_num.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->book_num = $row['book_num'];
            $this->book_starting_time = $row['book_starting_time']; 
            $this->book_ending_time =  $row['book_ending_time']; 
            $this->book_date = $row['book_date'];
            $this->user_id =  $row['user_id'];  
        }
        else {
             $this->stringLog .=  'ERROR: Could not execute query: sql. ' .$this->mysqli->error;
        }
        
    }
    
    public function displayBooking($book_num){
        $this->assignBookingAttributes($book_num);
        //code
    }
    
    public function displayAllBookings() {
        $query ='SELECT
                FROM booking
                ORDER BY book_num DESC';
        $response = '';
        if($result = $this->mysqli->query($query)){
                if($result->num_rows > 0){
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                $this->book_num = $row['book_num'];
                                $this->book_starting_time = $row['book_starting_time'];
                                $this->book_ending_time = $row['book_ending_time'];
                                $this->book_date = $row['book_date'];
                                $this->user_id = $row['user_id'];
                                $response .= $this->toString();
                        }
                        $result->close();
                }
        }else {
            //Error
            $this->stringLog .= "ERROR: Could not execute query: sql. " . $this->mysqli->error;
        }
        return trim($response);
        
    }
    
   

    public function book_transport_as_chc($book_starting_time, $book_ending_time, $book_date) {
        //code
    }
    
    public function book_transport_as_staff($book_starting_time, $book_ending_time, $book_date) {
        //code
    }
    
    public function book_transport_as_student($book_starting_time, $book_ending_time, $book_date){
        //code
    }
    
    public function cancelBooking($book_num){
        $this->assignBookingAttributes($book_num);
        //code
        $this->removeTransport($this->trans_no);
    }
    
    public function toString(){
        //code
    }
}
