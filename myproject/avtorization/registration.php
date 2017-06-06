<?php
// Registration
//Sent information to the database from the form
if (isset($_POST['submit2'])) {
	try {
		//If the registration form is sent and all fields are not empty
		if (!empty($_REQUEST['password2']) and !empty($_REQUEST['login2']) and !empty($_REQUEST['email2']) and !empty($_REQUEST['name']) and !empty($_REQUEST['surname']) and !empty($_REQUEST['date_of_birth']) and !empty($_REQUEST['gender'])) {
			$login = $_REQUEST['login2']; 
			$password = $_REQUEST['password2']; 
			$email = $_REQUEST['email2']; 
			$name = $_REQUEST['name'];
			$surname = $_REQUEST['surname'];
			$date_of_birth = $_REQUEST['date_of_birth'];
			$gender = $_REQUEST['gender'];
					
			// Performs the validation to freedom login. The response from the database record into a variable $row
			$sql = 'SELECT * FROM users  WHERE login="'.$login.'"';
			$isLoginFree = $basa->query($sql);
			$row = $isLoginFree->fetch(PDO::FETCH_ASSOC);
						
			try {
				//If $row is empty - the login is free
				if (!isset($row['login'])) {
					//Generate the salt using the function generateSalt() and salt the password
					$salt = generateSalt(); 
					$saltedPassword = md5($password.$salt); 

					// Added information to the database from the form
					$sql2 = 'INSERT INTO users SET login="'.$login.'", password="'.$saltedPassword.'", salt="'.$salt.'", email="'.$email.'", name="'.$name.'", surname="'.$surname.'", date_of_birth="'.$date_of_birth.'", gender="'.$gender.'"';
					$prep = $basa->prepare($sql2);
					$basa->query($sql2);
					//The message about the successful registration
					echo 'Вы успешно зарегистрированы!';
				}
				//If $row is not empty - the login is not free
				else {
					//Generate the exception
			        throw new Exception('Этот логин уже занят!');
		     	}
			}
			catch (Exception $ex6) {
				//Print the exception message
				$exRegistration6 = $ex6->getMessage();
			}
		}
		//Not filled any of the fields
		else {
			//Generate the exception
	        throw new Exception('Заполните все поля!');
     	}
	}
	catch (Exception $ex5) {
		// Print the exception message
		$exRegistration5 = $ex5->getMessage();
	}
}




