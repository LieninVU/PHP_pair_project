<?php
require 'session_manager.php';
session_start();
$_SESSION['id'] = session_id();
$_SESSION['name'] = session_name();
// var_dump($_SESSION);
// die();
$session_manager = new session_manager();
try{
    if($session_manager->validate_session($_SESSION['id'])){
        
    }
    else{
        header('location: index.html');
        exit();
    }
} catch(Exception $ex){
    echo 'Exception: ' . $ex->getMessage();
}
phpinfo();
?>