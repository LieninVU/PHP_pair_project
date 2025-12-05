<?php
require 'session_manager.php';
session_start();
// var_dump($_SESSION);
// die();
$session_manager = new session_manager();
try{
    if($session_manager->validate_session(session_id())){
        
    }
    else{
        header('location: index.html');
    }
} catch(Exception $ex){
    echo 'Exception: ' . $ex->getMessage();
}
phpinfo();
?>