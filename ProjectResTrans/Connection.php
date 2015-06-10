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