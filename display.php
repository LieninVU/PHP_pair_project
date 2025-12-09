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

$userArr = $manager->get_user_by_login_password($login, $password);
if ($userArr && count($userArr) > 0) {
    $user = $userArr[0];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['login'] = $user['login'];
    $_SESSION['id'] = session_id();
    $session_manager->add_session($user['id'], session_id());
    header('Location: index.php');
    exit();
} else {
    $manager->add_user($login, $password);
    header('Location: regestration.html');
    exit();
}
?>