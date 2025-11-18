<?php
require 'account_manager.php';

// use MyNamespace\users_manager;


$login = $_POST['firstname'];
$password = $_POST['lastname'];
$manager = new users_manager('database.db');
echo 'Your name: <b>'.$login . ' ' . $password . '</b>';
if($manager->get_user_by_login_password($login, $password)) {
    header('Location: index.php');

}
else {
    $manager->set_to_file($login, $password);
    header('Location: index.php');
}
?>