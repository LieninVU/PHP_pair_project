<?php
session_start();
	
// echo $_COOKIE["PHPSESSID"];
echo session_id();
echo session_name();
// $_SESSION["name"] = "Sam";
// $_SESSION["age"] = 41;
// echo "Данные сохранены в сессии";

?>