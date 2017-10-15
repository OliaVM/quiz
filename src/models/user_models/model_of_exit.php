<?php
class ExitModel {
	public function do_exit() {
		if (isset($_POST['exit'])) {
			if (!empty($_SESSION['user_id']) and isset($_SESSION['user_id'])) {
				// destroy of the session
				unset($_SESSION['count']);
				session_destroy();
				   
			    //Delete the cookies
				setcookie('login', '', time()); 
				setcookie('key', '', time()); 

				header("Location: /index.php"); 
			    exit; 
			}
		}
	}
}




