<?php
class ExceptionRegFormFieldsEmptinessControl extends Exception { }
class ExceptionLoginFreeControl extends Exception { }
class ExceptionFormDataTrueValidation extends Exception { }

// Registration
class RegModel {
	//Sent information to the database from the form
	public function regFormSendingControl($connect) {
		try {
			if (isset($_POST['submit2'])) {
				$this->regFormFieldsEmptinessControl($connect);
			}
		}
		catch (Exception $exReg) {
			return $exReg->getMessage();
		}
	}

	public function	regFormFieldsEmptinessControl($connect) {
		try {
			//If the registration form is sent and all fields are not empty
			if (!empty($_REQUEST['password2']) and !empty($_REQUEST['login2']) and !empty($_REQUEST['email2']) and !empty($_REQUEST['name']) and !empty($_REQUEST['surname']) and !empty($_REQUEST['date_of_birth']) and !empty($_REQUEST['gender'])) {
				$login = $_REQUEST['login2']; 
				$password = $_REQUEST['password2']; 
				$email = $_REQUEST['email2']; 
				$name = $_REQUEST['name'];
				$surname = $_REQUEST['surname'];
				$date_of_birth = $connect->quote($_REQUEST['date_of_birth']);
				$gender = $_REQUEST['gender'];
			
				// Performs the validation to freedom login. The response from the database record into a variable $row
				$sql = 'SELECT * FROM users  WHERE login="'.$login.'"';
				$isLoginFree = $connect->prepare($sql);
				$isLoginFree->execute();
				$row = $isLoginFree->fetch(PDO::FETCH_ASSOC);

				$this->loginFreeControl($connect, $row, $password, $login, $email, $name, 
				$surname, $date_of_birth, $gender);
			}
			//Not filled any of the fields
			else {
				//Generate the exception
		        throw new ExceptionRegFormFieldsEmptinessControl('Заполните все поля!');
	     	}
		}
		catch (ExceptionRegFormFieldsEmptinessControl $exReg1) {
			throw $exReg1;
		}
	}

	public function	loginFreeControl($connect, $row, $password, $login, $email, $name, 
				$surname, $date_of_birth, $gender) {
		try {
			//If $row is empty - the login is free
			if (!isset($row['login'])) {
				$this->formDataTrueValidation($connect, $row, $password, $login, $email, $name, 
				$surname, $date_of_birth, $gender);
			}
			//If $row is not empty - the login is not free
			else {
				//Generate the exception
		        throw new ExceptionLoginFreeControl('Этот логин уже занят!');
	     	}
		}
		catch (ExceptionLoginFreeControl $exReg2) {
			//For print the exception message
			throw $exReg2;
		}
	}

	public function	formDataTrueValidation($connect, $row, $password, $login, $email, $name, 
				$surname, $date_of_birth, $gender) {
		try	{
			if (preg_match("/[a-zA-Z0-9а-яА-Я]{3,20}/", $_REQUEST['login2'])) {
				//Generate the salt using the function generateSalt() and salt the password
				$salt = generateSalt(); 
				$saltedPassword = md5($password.$salt); 

				// Added information to the database from the form
				$sql2 = 'INSERT INTO users (login, password, salt, email, name, 
				surname, date_of_birth, gender) VALUES (:login, :password, :salt, :email, :name, :surname, :date_of_birth, :gender)';
				$prep = $connect->prepare($sql2);
				$prep->bindValue(':login', $login, PDO::PARAM_STR);
				$prep->bindValue(':password', $saltedPassword, PDO::PARAM_STR);
				$prep->bindValue(':salt', $salt, PDO::PARAM_STR);
				$prep->bindValue(':email', $email, PDO::PARAM_STR);
				$prep->bindValue(':name', $name, PDO::PARAM_STR);
				$prep->bindValue(':surname', $surname, PDO::PARAM_STR);
				$prep->bindValue(':date_of_birth', $date_of_birth, PDO::PARAM_STR);
				$prep->bindValue(':gender', $gender, PDO::PARAM_STR);
				$prep->execute(); 
				//The message about the successful registration
				echo "<script language='javascript'> alert('Вы успешно зарегистрированы!'); </script>";
			}
			else {
				 throw new ExceptionFormDataTrueValidation('Вы можете использовать только буквы и цифры. Число буквенных символов должно быть не меньше трех и не больше 20');
			}
		}
		catch (ExceptionFormDataTrueValidation $exReg3) {
			//For print the exception message
			throw $exReg3;
		}
	}
}