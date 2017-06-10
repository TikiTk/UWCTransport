<?php
require_once('Config.php');
/*
 * Project 2015.
 */

/**
 * Description of Connection
 *
 * @author uwcTransProjectTeam
 */
class Connection {
    public $mysqli;	
    public $stringLog;
    //constructor open database connection
    public function __construct() {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        if ($this->mysqli === false) {
                die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    }   
    
    //destructor closes database connection
    public function __destruct() {
        $this->mysqli->close();
        
    }
    
}


require_once('Connection.php');

/**
 * Description of User
 *
 * @author uwcTransProjectTeam
 */
class User extends Connection {
    public $user_id;
    public $user_fname;
    public $user_lname;
    public $user_cell;
    public $user_student_number;
    public $user_addrs;
    private $user_pass;
    
    public function __construct() {
        parent::__construct();
    }   
    
    public function __destruct() {
    }
    
    public function assignUserAttributes($user_id){
        $query = 'SELECT *  '
                . ' FROM user WHERE user_id= "'.$user_id.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->user_id = $row['user_id'];
            $this->user_fname = ucwords($row['user_fname']); 
            $this->user_lname =  ucwords($row['user_lname']); 
            $this->user_cell =  $row['user_cell']; 
            $this->user_student_number =  $row['user_student_number']; 
            $this->user_pass =  $row['user_pass']; 

        }
        else {
             $this->stringLog .=  'Oops!: Could not execute query: sql. ' .$this->mysqli->error;
        }
    
    }
    public function assignUserEmailAttributes($user_student_number){
        $query = 'SELECT *'
                . ' FROM user WHERE user_student_number= "'.$user_student_number.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->user_id = $row['user_id'];
            $this->user_fname = $row['user_fname']; 
            $this->user_lname =  $row['user_lname']; 
            $this->user_cell =  $row['user_cell']; 
            $this->user_student_number =  $row['user_student_number']; 
            $this->user_pass =  $row['user_pass'];
        }
        else {
             $this->stringLog .=  'Oops!: Could not execute query: sql. ' .$this->mysqli->error;
        }
        
    
    }
    
    public function displayUser($user_id) {
        //code
    }
    
    public function displayAllUsers() {
        //code
    }
    
    public function register_user($user_fname, $user_lname, $user_cell, $user_student_number, $user_pass){
        $user_fname = htmlentities($this->mysqli->real_escape_string($user_fname));
        $user_lname = htmlentities($this->mysqli->real_escape_string($user_lname));
        $user_cell = htmlentities($this->mysqli->real_escape_string($user_cell));
        $user_student_number = htmlentities($this->mysqli->real_escape_string($user_student_number));
        $user_pass = htmlentities($this->mysqli->real_escape_string($user_pass));
        
        $query = 'INSERT INTO user(user_fname, user_lname, user_cell, user_student_number, user_pass)'.
        ' VALUES (
        "'.$user_fname.'",
        "'.$user_lname.'",
        "'.$user_cell.'",
        "'.$user_student_number.'",
        "'.$user_pass.'") ';
        if ($this->mysqli->query($query) === true) {
            $this->assignUserEmailAttributes($user_student_number);
            $this->stringLog .=  $this->mysqli->affected_rows . ' user updated.';
            //login Go to Home page.
        } else {
            //user exist
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error;
        }
    }
    
    public function login_user($user_student_number, $user_pass) {
        $user_student_number = htmlentities($this->mysqli->real_escape_string($user_student_number));
        $user_pass = htmlentities($this->mysqli->real_escape_string($user_pass));
        
        $query = 'SELECT * '
                . ' FROM user WHERE user_student_number= "'.$user_student_number.'"';
        if ($result = $this->mysqli->query($query)) {
            
            $row = $result->fetch_assoc();
            $this->user_student_number =  $row['user_student_number']; 
            $this->user_pass =  $row['user_pass']; 
            if($this->user_student_number){
                if($this->user_pass == $user_pass){
                    //login Go to home page.
                    $this->assignUserEmailAttributes($user_student_number);
                    $this->stringLog .= ''.$this->user_fname;
                }else{
                    $this->stringLog .= 'Wrong password. ';
                }
            }else{
                $this->stringLog .= 'The email '.$user_student_number.' does not exists. ';
            }

        }
        else {
            //somthing went wrong.
           $this->stringLog .=  'Oops!: Could not execute query: sql. ' .$this->mysqli->error;
        }
    }
    
}

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


/*
 * Project 2015.
 */

/**
 * Description of Transport
 *
 * @author uwcTransProjectTeam
 */
class Transport {
    public $trans_no;
    public $trans_type;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function __destruct() {
        $this->mysqli->close();    
    }
    
    public function assignTransportAttributes($trans_no){
        $query = 'SELECT *'
                . ' FROM transport WHERE trans_no = "'.$trans_no.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->trans_no = $row['trans_no'];
            $this->trans_type = $row['trans_type'];
        }
        else {
             $this->stringLog .=  'ERROR: Could not execute query: sql. ' .$this->mysqli->error;
        }
    }
    
    public function get_chc_transport(){
        //code
    }
    
    public function get_staff_transport() {
        //code
    }
    
    public function get_student_transport(){
        //code
    }
            
}


