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
$connect  = getDbConnection($dsn, $user, $password);


$connect->exec("set names utf8");

//Cookie existence check
require_once '/var/www/html/myproject/avtorization/cookies.php';
//Authorization
require_once '/var/www/html/myproject/avtorization/avtorization.php';
// Registration
require_once '/var/www/html/myproject/avtorization/registration.php';

/*
//если пользователь выбрал тему, переписываем ее название, так чтобы первая буква стала заглавной
if (isset($_POST['quiz_theme_go'])) {
	$subject = $_POST['subject'];
	if (!function_exists('mb_ucfirst') && extension_loaded('mbstring')) {
	    function mb_ucfirst($str, $encoding='UTF-8') {
	        $str = mb_ereg_replace('^[\ ]+', '', $str);
	        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
	               mb_substr($str, 1, mb_strlen($str), $encoding);
	        return $str;
	    }
	}
	$str = $subject;
	echo $str = mb_ucfirst($str) . "<br>";
	//или
	//echo mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
}
*/

//функция - Вывод вопросов из базы данных
function getList($connect, $sql) { 
	$notes_list = $connect->query($sql);
	return $notes_list;
}

//Выбор темы викторины пользователем и отображжение вопросов на экране
if (isset($_GET['subject_go'])) {
	$subject = $_GET['subject_go'];
	$sql = 'SELECT * FROM questions WHERE theme="'.$subject.'"'; 
	$questionList = getList($connect, $sql);
}

//обновляем записи с результатами пользователя 
function submitDb($sql6, $connect, $user_points, $game_number, $i) {
	$prep = $connect->prepare($sql6);
	$prep->bindValue(':game_number', $game_number, PDO::PARAM_INT);
	$prep->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
	$prep->bindValue(':question_number_id', $_POST['btn_ans'.$i], PDO::PARAM_INT);
	$prep->bindValue(':user_points', $user_points, PDO::PARAM_INT);
	$arr = $prep->execute(); 
	return $arr;
}

// если пользователь нажал на кнопку ответить
if (isset($_POST['button'])) { 
	for ($y = 0; $y < count($_SESSION['var']); $y++) { 
		$i = $y +1;
		$question_number_id = $_SESSION['var'][$y]; 
				
		//находим максимальный прошлый номер игры
		$sql1 = 'SELECT MAX(game_number) FROM usersAnswers WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$question_number_id.'"'; 
		$sth1 = $connect->query($sql1);
		$maxGameNumber = $sth1->fetch(PDO::FETCH_ASSOC);
		//$maxGameNumber = (int)$maxGameNumber;
		
		//if (!empty($_SESSION['answer'][$y])) { // если ответ пользователя не пустой
		//Рассчитываем номер игры пользователя
		//выбираем строку с номером игры из базы данных
		$sql2 = 'SELECT game_number FROM usersAnswers WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$question_number_id.'"';
		$sth2 = $connect->query($sql2);
		$rowUsersAnswers = $sth2->fetch(PDO::FETCH_ASSOC);
		//если строка с номером игры пустая, то создаем номер игры и записываем его в БД
		if (empty($rowUsersAnswers['game_number'])) {	
			$game_number = 1;
		}
		else { //иначе номер игры = максимальный  номер + 1
			$game_number = $maxGameNumber['MAX(game_number)'] + 1;
		}
		
		//проверяем правильность ответа пользователя
		//выбираем правильный ответ на заданный вопрос в базе данных
		$sql3 = 'SELECT answer FROM questions WHERE question_number_id="'.$question_number_id.'" and theme="'.$subject.'"'; 
		$sth3 = $connect->query($sql3);
		$rowAnswer = $sth3->fetch(PDO::FETCH_ASSOC); 
		

		$save_answer = $_SESSION['answer'][$y]; //достаем ответ пользователя на вопрос из $_SESSION['answer'][$y]
		$save_answer = mb_strtolower($save_answer,'UTF-8'); //преобразуем ответ пользователя в строчные буквы
		$save_answer = trim($save_answer); //убираем проблееы в конце и в начале ответа пользователя
		if ($rowAnswer['answer'] == $save_answer) { //если ответ пользователя правильный
				$sql4 = 'SELECT points FROM questions WHERE question_number_id="'.$question_number_id.'"';
				$sth4 = $connect->query($sql4);
				$user_points_row = $sth4->fetch(PDO::FETCH_ASSOC);//получаем из базы данных количество очков за правильный ответ
				$user_points = $user_points_row['points']; // 
				settype($user_points, 'integer');
		}	
		else { //если ответ пользователя неправильный, он получает 0 очков
				$user_points = 0;
		}
		//Добавляем в базу данных количество очков пользователя за ответ на вопрос
		$sql5="INSERT INTO usersAnswers (game_number, user_id, question_number_id, user_points) VALUES (:game_number, :user_id, :question_number_id, :user_points )"; 
		submitDb($sql5, $connect, $user_points, $game_number, $i);	
	}

	//Считаем результат игры
	$sql6 = 'SELECT SUM(user_points) FROM usersAnswers WHERE game_number="'.$game_number.'"';
	$sth6 = $connect->query($sql6);
	$rowAnswer6 = $sth6->fetch(PDO::FETCH_ASSOC); 
	$points = $rowAnswer6['SUM(user_points)'];
	$countRight = $points/10;
	//echo "№" .$game_number;
	//print('<sqript language="javascript">window.alert("'.$points.'");</sqript>');
	echo "<script language='javascript'> alert('вы набрали ' + ".$points." + ' баллов. Количество правильных ответов: ' + ".$countRight."); </script>";

	//Добавляем результат игры в базу данных
	$sql7="INSERT INTO results (user_id, game_number, theme, points) VALUES (:user_id, :game_number, :theme, :points)"; 
	$prep7 = $connect->prepare($sql7);
	$prep7->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
	$prep7->bindValue(':game_number', $game_number, PDO::PARAM_INT);
	$prep7->bindValue(':theme', $_POST['btn_theme'], PDO::PARAM_STR); 
	$prep7->bindValue(':points', $points, PDO::PARAM_INT);
	$arr7 = $prep7->execute(); 
}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link href="/style/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="/style/style.css" rel="stylesheet" type="text/css"/>
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





