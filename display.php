<?php
require 'account_manager.php';
require 'session_manager.php';
// use MyNamespace\users_manager;

session_start();
$_SESSION['id'] = session_id();
$_SESSION['name'] = session_name();
try{
    $session_manager = new session_manager();
    $manager = new users_manager('database.db');
} catch(Exception $ex){
    echo $ex->getMessage();
}


$login = $_POST['firstname'];
$password = $_POST['lastname'];
echo 'Your name: <b>'.$login . ' ' . $password . '</b>';

if($manager->get_user_by_login_password($login, $password)) {
    $session_manager->create_session($login);
    // var_dump($_SESSION);
    // die();
    header('Location: index.php');

}
else {
    $manager->add_user($login, $password);
    header('Location: regestration.html');
}
?>