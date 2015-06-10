<?php
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

?>