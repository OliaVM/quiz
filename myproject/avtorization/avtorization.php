<?php
// Authorization
// Get the information from the database and compare with input user data
if (isset($_POST['submit'])) {
	try {
		//If the fields username and password filled
		if (!empty($_REQUEST['password']) and !empty($_REQUEST['login'])) {
			$login = $_REQUEST['login']; 
			$password = $_REQUEST['password']; 
			$sql = 'SELECT * FROM users WHERE login="'.$login.'"';
			$sth = $connect->query($sql);
			$rowUser = $sth->fetch(PDO::FETCH_ASSOC); //Преобразуем ответ из БД в строку массива
						
			try {
				//If the database returned a non-empty answer, it means this login exist
				if (isset($rowUser['login'])) {
					$salt = $rowUser['salt'];
					//Salt the password from the form
					$saltedPassword = md5($password.$salt);
					try {
						//If salt password from the database matches with the salted password from the form
						if ($rowUser['password'] == $saltedPassword) {
							// Write to the session information about avtorization
							$_SESSION['auth'] = true; 
							$_SESSION['user_id'] = $rowUser['user_id']; 
							$_SESSION['id'] = $rowUser['id']; 
							$_SESSION['login'] = $rowUser['login']; 
							$_SESSION['password'] = $rowUser['password']; 
														
							//Verify whether the checkbox 'Remember me' is clicked 
							if (!empty($_REQUEST['remember']) and $_REQUEST['remember'] == 1 ) {
								$key = generateSalt(); 
								setcookie('login', $rowUser['login'], time()+60*60*24*30); 
								setcookie('key', $key, time()+60*60*24*30); 
								
								$sql = 'UPDATE users SET cookie="'.$key.'" WHERE login="'.$login.'"';
								$keys = $connec->query($sql);
							}
							//Counter sessions
							if (!isset($_SESSION['count'])) {
								$_SESSION['count'] = 0;
							}	
							else {
								$_SESSION['count']++;  
							}
							
						}
						else {
							//Generate the exception
							throw new Exception('Не верно введен логин или пароль');
						}
					}
					catch (Exception $ex3) {
							//Print the exception message
							$exAvtoriz3 = $ex3->getMessage();
					}
				}
				else {
					//Generate the exception
					throw new Exception('Пользователь с таким именем не зарегистрирован');
				}
			}
			catch (Exception $ex4) {
				//Print the exception message
				$exAvtoriz4 = $ex4->getMessage();
			}
		}
		else {
			// Generate the exception
			throw new Exception('Запоните все поля!');
		}
	}
	catch (Exception $ex8) {
		//Print the exception message
		$exAvtoriz8 = $ex8->getMessage();
	}
}

//Запоминаем введенные пользователем ответы на вопросы и номера вопросов
if (isset($_POST['button'])) { 
	if (isset($_SESSION['login']) && isset($_SESSION['password'])) {
		//Записываем в сессию номера вопросов
		$_SESSION['var'] = [];
		$sql0 = 'SELECT question_number_id FROM questions';
		$sth0 = $connect->query($sql0);
		$row0 = $sth0->fetchAll(PDO::FETCH_COLUMN); //количество вопросов
		$z = 0;
		for ($i = 0; $i < count($row0); $i++) {
			$z = $z + 1;
			$z = (string)$z;
			if (isset($_POST['btn_ans'.$z])) {
				$_SESSION['var'][$i] = $_POST['btn_ans'.$z];
				$z = (int)$z;
			}
		}

		//Записываем в сессию ответы пользователя
		$_SESSION['answer'] = [];
		for ($i = 0; $i < count($row0); $i++) {
		$z = $i + 1;
		$z = (string)$z;
			if (isset($_POST["answer_from_form".$z])) {
				$_SESSION['answer'][$i] = $_POST["answer_from_form".$z];
				$z = (int)$z;
			}
		}
	}
}