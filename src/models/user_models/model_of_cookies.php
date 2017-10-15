<?php
class CookiesModel {
	public function cookiesIssetControl($connect) {
		if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
			//If a cookies is not empty
			if (!empty($_COOKIE['login']) and !empty($_COOKIE['key'])) {
				//Save the username and key from the COOKIE to the variables
				$login = $_COOKIE['login']; 
				$key = $_COOKIE['key']; 

				$sql = 'SELECT*FROM users WHERE login="'.$login.'" AND cookie="'.$key.'"';
				$sth = $connect->query($sql);
				$rowUser = $sth->fetch(PDO::FETCH_ASSOC); //String of array

				//If the database returned not empty response - login and key from cookies is true
				if (!empty($rowUser)) {
					$_SESSION['auth'] = true; 
					$_SESSION['login'] = $rowUser['login']; 
					$_SESSION['password'] = $rowUser['password']; 
					$_SESSION['user_id'] = $rowUser['user_id'];
				}
			}
		}
	}
}

$cookiesAuth = new CookiesModel();
$cookiesAuth->cookiesIssetControl($connect);