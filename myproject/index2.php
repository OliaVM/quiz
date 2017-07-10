<?php
session_start();
require_once '/var/www/html/src/autoload.php';
//Соединение с базой данных
//var_dump($_SESSION['login']);


///var_dump($_SESSION['var'][1]);
$user = 'root';
$password = 'vagrant';
$dsn = 'mysql:host=localhost; dbname=quize2';
function getDbConnection($dsn, $user, $password) {
	return new PDO($dsn, $user, $password);
}

$basa  = getDbConnection($dsn, $user, $password);
$basa->exec("set names utf8");

/*
$z = 1;
$z = (string)$z;
$x = 'btn_ans'.$z;
echo "$x";
var_dump($x);
*/


//Вывод вопросов из базы данных
function getList($basa, $sql) { 
	$notes_list = $basa->query($sql);
	return $notes_list;
}

//Объединение таблиц. Способ1
$sql = "SELECT * FROM questions INNER JOIN usersAnswers using(id)";
$questionList = getList($basa, $sql);

//Объединение таблиц. СПОСОБ2
//$sql2 = "SELECT * FROM questions, usersAnswers WHERE questions.id = usersAnswers.id";
//$questionList = getList($basa, $sql2);
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

<div class="row-fluid">
	    <div class="span10" id="box2">
	    	
		</div>
	</div>
	<?php require_once '/var/www/html/src/core/form/exit_button.php'; ?>
	<div class="row-fluid">
		
					<!-- Вывод записей на экран -->
					<p><?php $points = 0; ?></p>
					<?php foreach ($questionList as $row): ?>
					<br> 
					<p><?php $points= $row['points'] + $points ; ?></p>
					<p><?php echo $row['question_number_id']; ?></p>
					<p><?php echo $row['question']; ?></p>
					<p><h2>Очки <?php echo $row['user_points']; ?></h2></p>
					<?php endforeach; ?>
		
		</div>
	</div>
