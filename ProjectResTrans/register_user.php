
<?php
 if (isset($_POST['email'])) {
    require_once('./User.php');
    require_once('./Booking.php');
    $user = new User();
    
    $email = trim($_POST['email']);
    
    $user_name = trim($_POST['user_name']);
    
    $user_type = trim($_POST['user_type']);
    
    // check input
    if (empty($email)) {
          $user->stringLog .= 'Please enter your user type. ';
          echo '{"report": "'.$user->stringLog .'"}';
          return;
    }
    elseif (empty($user_name)) {
          $user->stringLog .= 'Please enter your user type. ';
          echo '{"report": "'.$user->stringLog .'"}';
          return;
    }
    elseif (empty($user_type)) {
          $user->stringLog .= 'Please enter your user type. ';
          echo '{"report": "'.$user->stringLog .'"}';
          return;
    }else{
        // attempt database connection
        // escape special characters in input
        $email = $user->mysqli->real_escape_string($email);
        $user_name = $user->mysqli->real_escape_string($user_name);
        $user_type =  $user->mysqli->real_escape_string($user_type);
        // check if emails exists
        $user->login_user($email, '1');
    }
    if(!$user->user_id){
        /*register user*/
        $user->register_user($user_name, "", "27", $email, '1', $user_type);
        $user->login_user($email, '1');

    }
    elseif($user->user_id) {
        session_start();
        session_destroy();
        session_start();
        
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['new_user'] = false;
        $_SESSION['welcome'] = true;
        setcookie('user_id', $user->user_id, time()+86400, '/');
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
    echo '{"report": "No email address"}';
}
?>

