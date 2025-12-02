<?php
$path = 'session.db';
session_start();
$session_manager = new session_manager($path);
try{
    if($session_manager->validate_session(session_id())){

    }
    else{
        header('location: display.php');
    }
}
phpinfo();
?>