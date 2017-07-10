<?php
session_start();
if (!empty($_SESSION['id']) and isset($_SESSION['id'])) {
	// destroy of the session
	unset($_SESSION['count']);
	unset($_SESSION['login']);
	unset($_SESSION['password']);
	session_destroy();
	   
    //Delete the cookies
	setcookie('login', '', time()); 
	setcookie('key', '', time()); 

	header("Location: http://myproject.local/index.php"); 
	//header("Location: http://192.168.50.5/index.php");
    exit; 
}
?>



