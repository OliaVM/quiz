<?php
// Authorization
class ExceptionFieldsEmptinessControl extends Exception { }
class ExceptionLoginExistenceControl extends Exception { }
class ExceptionUserPasswordControl extends Exception { }
// Get the information from the database and compare with input user data
class AuthClassModel {
	public function formSendingControl($connect) {
		try {
			if (isset($_POST['submit'])) {
				$this->fieldsEmptinessControl($connect);
			} 
		}
		catch (Exception $e) {
			//Print the exception message
			return $e->getMessage();
		}
    } // end of formSendingControl

	public function fieldsEmptinessControl($connect) {
		try {
			//If the fields username and password filled
			if (!empty($_REQUEST['password']) and !empty($_REQUEST['login'])) {
				$login = $_REQUEST['login']; 
				$password = $_REQUEST['password']; 
				$sql = 'SELECT * FROM users WHERE login="'.$login.'"';
				$sth = $connect->prepare($sql);
				$sth->execute();
				$rowUser = $sth->fetch(PDO::FETCH_ASSOC); //Преобразуем ответ из БД в строку массива
				$this->loginExistenceControl($rowUser, $password, $login, $connect);
				return [$rowUser, $password];
			}
			else {
				// Generate the exception
				throw new ExceptionFieldsEmptinessControl('Запоните все поля!');
			}
		}
		catch (ExceptionFieldsEmptinessControl $ex) {
			//Print the exception message
			throw $ex;
		}
	} // end of fieldsEmptinessControl

	public function loginExistenceControl($rowUser, $password, $login, $connect) {	//$rowUser, $password	
		try {
			//If the database returned a non-empty answer, it means this login exist
			if (isset($rowUser['login'])) {
				$salt = $rowUser['salt'];
				//Salt the password from the form
				$saltedPassword = md5($password.$salt);
				$this->UserPasswordControl($rowUser, $saltedPassword, $login, $connect);
				return $saltedPassword;
			}
			else {
				//Generate the exception
				throw new ExceptionLoginExistenceControl('Пользователь с таким именем не зарегистрирован!');
			}
		}
		catch (ExceptionLoginExistenceControl $ex4) {
			//Print the exception message
			throw  $ex4;
		}
		
	} //end of loginExistenceControl

	public function UserPasswordControl($rowUser, $saltedPassword, $login, $connect) { 	
		try {
			//If salt password from the database matches with the salted password from the form
			if ($rowUser['password'] == $saltedPassword) {
				// Write to the session information about avtorization
				$_SESSION['auth'] = true; 
				$_SESSION['user_id'] = $rowUser['user_id'];
				$_SESSION['login'] = $rowUser['login']; 
				$_SESSION['password'] = $rowUser['password']; 
				$this->setUserCookie($rowUser, $login, $connect);
				$this->getSessionCount();
			}
			else {
				//Generate the exception
				throw new ExceptionUserPasswordControl('Не верно введен логин или пароль!');
			}
		}
		catch (ExceptionUserPasswordControl $ex3) {
				//Print the exception message
				throw $ex3;
		}
	
	} //end of UserPasswordControl


	public function setUserCookie($rowUser, $login, $connect) {	//	$rowUser, 							
		//Verify whether the checkbox 'Remember me' is clicked 
		if (!empty($_REQUEST['remember']) and $_REQUEST['remember'] == 1) {
			$key = generateSalt(); 
			setcookie('login', $rowUser['login'], time()+60*60*24*30); 
			setcookie('key', $key, time()+60*60*24*30); 
			$sql = 'UPDATE users SET cookie="'.$key.'" WHERE login="'.$login.'"';
			$keys = $connect->query($sql);
		}
	}

	public function getSessionCount() {
		//Counter sessions
		if (!isset($_SESSION['count'])) {
			$_SESSION['count'] = 0;
		}	
		else {
			$_SESSION['count']++;  
		}
		return $_SESSION['count'];
	}
}

