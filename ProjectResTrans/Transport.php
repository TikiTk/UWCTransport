<?php

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
