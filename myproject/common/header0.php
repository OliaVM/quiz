<?php
session_start();
require_once '/var/www/html/src/autoload.php';
//Соединение с базой данных
$user = 'creator';
$password = 'password';
$dsn = 'mysql:host=localhost; dbname=quiz';
function getDbConnection($dsn, $user, $password) {
	return new PDO($dsn, $user, $password);
}

$basa  = getDbConnection($dsn, $user, $password);
$basa->exec("set names utf8");

//Cookie existence check
require_once '/var/www/html/myproject/avtorization/cookies.php';
//Authorization
require_once '/var/www/html/myproject/avtorization/avtorization.php';
// Registration
require_once '/var/www/html/myproject/avtorization/registration.php';

//Вывод вопросов из базы данных
function getNotesList($basa, $sql2) { 
	$notes_list = $basa->query($sql2);
	return $notes_list;
}
$sql2 = "SELECT * FROM questions"; 
$notes = getNotesList($basa, $sql2);



if (isset($_POST['go'])) { // если пользователь нажал на кнопку ответить
	$sql = 'SELECT answer FROM questions WHERE question_number_id="'.$question_number_id.'"';
	$sth = $basa->query($sql);
	$rowUser = $sth->fetch(PDO::FETCH_ASSOC); //Преобразуем ответ из БД в строку массива
	if ($rowUser == $_POST['answer_from_form']) { //если ответ пользователя правильный
		$user_points = getNotesList($basa, "SELECT points FROM questions"); //получаем из базы данных количество очков за правильный ответ
		
		//считаем номер игры пользователя
		$sql2 = 'SELECT game_number FROM usersAnswers WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$_POST['question_number_id'].'"';
		$sth2 = $basa->query($sql2);
		$rowUsersAnswers = $sth2->fetch(PDO::FETCH_ASSOC);
		if (empty($rowUsersAnswers['game_number'])) {	//если строка с номером игры пустая, то создаем номер игры и записываем его в БД
			$sql3 = 'UPDATE usersAnswers  SET  game_number =:game_number WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$_POST['question_number_id'].'"'; //Записываем результат голосования
			$prep = $basa->prepare($sql3);

			$sql4 = 'SELECT MAX(game_number) FROM usersAnswers'; //находим максимальный прошлый номер игры
			if (empty($sql4)) {
				$game_number = 1;
				$prep->bindValue(':game_number', $game_number, PDO::PARAM_INT);
				$prep->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
				$prep->bindValue(':question_number_id', $_POST['question_number_id'], PDO::PARAM_INT);
				$prep->execute(); 
				$result = $prep->fetch(PDO::FETCH_ASSOC);
			}
			else {
				$game_number = $sql4;
				$game_number = $game_number + 1;
				$prep->bindValue(':game_number', $game_number, PDO::PARAM_INT);
				$prep->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
				$prep->bindValue(':question_number_id', $_POST['question_number_id'], PDO::PARAM_INT);
				$prep->execute(); 
				$result = $prep->fetch(PDO::FETCH_ASSOC);
				//header('Location: http://myproject.local/index.php')
			}
		}


		//обновляем записи с результатами пользователя и запись с лучшими результатами
		function submitDb($sql2, $basa, $user_points) {
			$prep = $basa->prepare($sql2);
			$prep->bindValue(':game_number', $game_number, PDO::PARAM_INT);
			$prep->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
			$prep->bindValue(':question_number_id', $_POST['question_number_id'], PDO::PARAM_INT);
			$prep->bindValue(':user_points', $user_points, PDO::PARAM_STR);
			$arr = $prep->execute(); 
			return $arr;
		}
		$sql2="INSERT INTO usersAnswers (game_number, user_id, question_number_id, user_points) VALUES (:game_number, :user_id, :question_number_id, :user_points )"; 
		submitDb($sql, $basa, $data);	
	}
	else {
		$user_points = 0;
	}
	
					
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link href="/style/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="/style/style2.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale = 1.0">
	<link type="text/css" rel="stylesheet" href="/style/bootstrap-responsive.css">
	<title>Викторина</title>
</head>
<body>
	<div class="row-fluid">
	    <div class="span10" id="box1">
	    	<h1>Викторина</h1></div>
		</div>





