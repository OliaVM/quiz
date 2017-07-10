<?php
session_start();
//var_dump($_SESSION['login']);
//echo 'SESSION1';
//var_dump($_SESSION['var']);
///var_dump($_SESSION['var'][1]);
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
//echo "Вы успешно вошли в систему под именем ".$_SESSION['login']. "<br>";
//echo 'user_id ='. $_SESSION['id'];
//var_dump($_SESSION['user_id']);

//$subject = "литература";
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

	// пробуем кириллицу в юникоде преобразовать функцией ucfirst
	//echo ucfirst($str) . '<br>';

	// пробуем кириллицу в юникоде преобразовать функцией ucwords
	//echo ucwords($str) . '<br>';

	// обрабатываем объявленной функцией mb_ucfirst()+
	echo $str = mb_ucfirst($str) . "<br>";

	// преобразовываем функцией mb_convert_case+
	//echo mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
}


//Вывод вопросов из базы данных
function getList($basa, $sql) { 
	$notes_list = $basa->query($sql);
	return $notes_list;
}
//Выбор рубрики
if (isset($_GET['subject_go'])) {
	$subject = $_GET['subject_go'];
	$sql = 'SELECT * FROM questions WHERE theme="'.$subject.'"'; 
	var_dump($sql);
	//$sql = 'SELECT * FROM questions WHERE theme="литература"'; 
	$questionList = getList($basa, $sql);
}

//обновляем записи с результатами пользователя и запись с лучшими результатами
function submitDb($sql6, $basa, $user_points, $game_number, $i) {
	$prep = $basa->prepare($sql6);
	$prep->bindValue(':game_number', $game_number, PDO::PARAM_INT);
	$prep->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
	$prep->bindValue(':question_number_id', $_POST['btn_ans'.$i], PDO::PARAM_INT);
	$prep->bindValue(':user_points', $user_points, PDO::PARAM_INT);
	$arr = $prep->execute(); 
	return $arr;
}


if (isset($_POST['button'])) { // если пользователь нажал на кнопку ответить
	
	for ($y = 0; $y < count($_SESSION['var']); $y++) { 
		$i = $y +1;
		$question_number_id = $_SESSION['var'][$y]; 
				
		//находим максимальный прошлый номер игры
		$sql4 = 'SELECT MAX(game_number) FROM usersAnswers WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$question_number_id.'"'; 
		$sth4 = $basa->query($sql4);
		//$maxGameNumber = $sth4->fetch();
		$maxGameNumber = $sth4->fetch(PDO::FETCH_ASSOC);
		//$maxGameNumber = (int)$maxGameNumber;
		//echo "max =" . $maxGameNumber;
		echo "max =";
		var_dump($maxGameNumber['MAX(game_number)']);

		//if (!empty($_SESSION['answer'][$y])) { // если ответ пользователя не пустой
		//$question_number_id = $_POST['btn_ans'.$i]; 
	
		//Рассчитываем номер игры пользователя
		//выбираем строку с номером игры из базы данных
		$sql5 = 'SELECT game_number FROM usersAnswers WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$question_number_id.'"';
		$sth5 = $basa->query($sql5);
		$rowUsersAnswers = $sth5->fetch(PDO::FETCH_ASSOC);
		if (empty($rowUsersAnswers['game_number'])) {	//если строка с номером игры пустая, то создаем номер игры и записываем его в БД
			$game_number = 1;
		}
		else { //иначе номер игры = максимальный  номер + 1
			$game_number = $maxGameNumber['MAX(game_number)'] + 1;
			echo "game_number =" . $game_number;
		}
		
		//проверяем правильность ответа пользователя
		//выбираем правильный ответ на заданный вопрос в базе данных
		$sql1 = 'SELECT answer FROM questions WHERE question_number_id="'.$question_number_id.'" and theme="'.$subject.'"'; 
		$sth = $basa->query($sql1);
		$rowAnswer = $sth->fetch(PDO::FETCH_ASSOC); 
		//echo "<br>";
		//echo "строка из БД =";
		//var_dump($rowAnswer);
		//echo "<br>";
		//echo 'ответ из формы =';
		//var_dump($_POST['answer_from_form']);
		$save_answer = $_SESSION['answer'][$y];
		$save_answer = mb_strtolower($save_answer,'UTF-8');
		$save_answer = trim($save_answer); 
		if ($rowAnswer['answer'] == $save_answer) { //если ответ пользователя правильный
				$sql20 = 'SELECT points FROM questions WHERE question_number_id="'.$question_number_id.'"';
				$sth20 = $basa->query($sql20);
				$user_points_row = $sth20->fetch(PDO::FETCH_ASSOC);//получаем из базы данных количество очков за правильный ответ
				$user_points = $user_points_row['points']; // 
				//$user_points = (int)$user_points;
				settype($user_points, 'integer');
				echo "<br>";
				echo "ответ правильный, user_points= $user_points";
				var_dump($save_answer);
				//echo $user_points;
		}	
		else { //если ответ пользователя неправильный, он получает 0 очков
				$user_points = 0;
				echo "ответ неправильный №". $question_number_id;
				var_dump($save_answer);
		}
		//Добавляем в базу данных количество очков пользователя за ответ на вопрос
		$sql6="INSERT INTO usersAnswers (game_number, user_id, question_number_id, user_points) VALUES (:game_number, :user_id, :question_number_id, :user_points )"; 
		submitDb($sql6, $basa, $user_points, $game_number, $i);	
			
		//}
	//}
	}

	//Считаем результат игры
	$sql7 = 'SELECT SUM(user_points) FROM usersAnswers WHERE game_number="'.$game_number.'"';
	$sth7 = $basa->query($sql7);
	$rowAnswer7 = $sth7->fetch(PDO::FETCH_ASSOC); 
	echo "sum = ";
	var_dump($rowAnswer7['SUM(user_points)']);
	echo "№" .$game_number;
	$points = $rowAnswer7['SUM(user_points)'];

	//Добавляем результат игры в базу данных
	$sql6="INSERT INTO results (user_id, game_number, theme, points) VALUES (:user_id, :game_number, :theme, :points)"; 
	$prep7 = $basa->prepare($sql6);
	$prep7->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
	$prep7->bindValue(':game_number', $game_number, PDO::PARAM_INT);
	$prep7->bindValue(':theme', $_POST['btn_theme'], PDO::PARAM_STR); //ДОБАВИТЬ
	$prep7->bindValue(':points', $points, PDO::PARAM_INT);
	$arr7 = $prep7->execute(); 
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





