<?php
header("Access-Control-Allow-Origin: http://localhost:8100");

 // if form submitted
 // check supplied login credentials
 // against database
 if (isset($_POST['email'])) {
     require_once('User.php');
     $user = new User();

     $email = trim($_POST['email']);
     $password = trim($_POST['pass']);
     // check input
     if (empty($email)) {
              $user->stringLog .= 'Please enter your email. ';
     }
     elseif (empty($password)) {
              $user->stringLog .= 'Please enter your password. ';
     }else{
         // attempt database connection
         // escape special characters in input
         $email = $user->mysqli->real_escape_string($email);
         $password = $user->mysqli->real_escape_string($password);
    
         // check if emails exists
         $user->login_user($email, $password);
     }
     if($user->user_id){
         session_start();
         session_destroy();
         session_start();

         $_SESSION['user_id'] = $user->user_id;
         $_SESSION['new_user'] = false;
         $_SESSION['welcome'] = true;
         echo '{"report": "true",'.
                '"user_id": "'.$user->user_id.'"}';
                  
         //header('Location: index.php');
     }else{
        // close connection
        unset($user->mysqli);
        setcookie('report2', $user->stringLog, time()+86400, '/');
        echo '{"report": "false"}';
        //echo '{"report": "'.$user->stringLog.'"}';
        //echo '{"report": "email='.$email.'pass='.$password.'"}';
        //header('Location: index.php');

     }
}else{
    echo '{"report": "Somthing went wrong"}';
}