<?php
session_start();
require_once __DIR__ . '/../src/autoload.php';
//Соединение с базой данных
$user = 'creator';
$password = 'password';
$dsn = 'mysql:host=localhost; dbname=quiz';
function getDbConnection($dsn, $user, $password) {
	return new PDO($dsn, $user, $password);
}
$connect  = getDbConnection($dsn, $user, $password);
$connect->exec("set names utf8");

//router (path from template.php to our page - TO PAGE content, authorization_page, ...)
if (isset($_GET['page_name'])) {
	$page_name = $_GET['page_name'];
	switch ($_GET['page_name']) {
		case 'content':
		$path = "/";
		break;
		case 'entrance_page':
		$path = "/../user_views/";
		break;
		case 'authorization_page':
		$path = "/../user_views/";
		break;
		case 'registration_page':
		$path = "/../user_views/";
		break;
	}
}
else {
	$page_name = "content";
	$path = "/";
}

//Add Model of COOKIES
require_once __DIR__ . '/../src/models/user_models/model_of_cookies.php'; //Cookie existence check

//Add Model, View, Controller of AUTHORIZATION
require_once __DIR__ .'/../src/models/user_models/model_of_authorization.php'; 
require_once __DIR__ .'/../src/views/user_views/authorizationView.php'; 
require_once __DIR__ .'/../src/controllers/AuthorizationController.php';

//Add Model, View of EXIT_from_session
require_once __DIR__ .'/../src/models/user_models/model_of_exit.php'; 
require_once __DIR__ .'/../src/views/user_views/view_of_exit.php'; 
require_once __DIR__ .'/../src/controllers/ExitController.php';


//Add Model, View of EXIT_from_session
require_once __DIR__ .'/../src/models/user_models/model_of_exit.php'; 
require_once __DIR__ .'/../src/views/user_views/view_of_exit.php'; 
require_once __DIR__ .'/../src/controllers/ExitController.php';

//Add Model, View, Controller of REGISTRATION
require_once __DIR__ .'/../src/models/user_models/model_of_registration.php'; 
require_once __DIR__ .'/../src/views/user_views/registrationView.php'; 
require_once __DIR__ .'/../src/controllers/RegistrationController.php';

class QuizModel {
	public $connect;
	public function __construct($connect) {
		$this->connect = $connect;
	}
	
	//Выбор темы викторины пользователем и отображение вопросов на экране
	public function showQuestions() {
		if (isset($_GET['subject_go'])) {
			$subject = $_GET['subject_go'];
			$sql = 'SELECT * FROM questions WHERE theme="'.$subject.'"'; 
			$questionList = $this->getList($sql);
			return [$subject, $questionList];
		}
	}
	
	//Вывод вопросов из базы данных
	public function getList($sql) { 
		return $this->connect->query($sql);
	}

	// если пользователь нажал на кнопку ответить
	public function buttonClickControl($subject, $connect) {
		if (isset($_POST['button'])) { 
			$this->rememberData($connect);
			$game_number = $this->getQuestNumberID($subject);
			$points = $this->get_UserResult_allQuest($game_number);
			$this->add_userGameResult_in_db($game_number, $points);
			
		}
	}
	
	public function rememberData($connect) {
		//Запоминаем введенные пользователем ответы на вопросы и номера вопросов
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
						echo "вопрос ". $_SESSION['var'][$i];
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
						echo "ответ ". $_SESSION['answer'][$i];
						$z = (int)$z;
					}
				}
			}
	}

	//start		
	//search and get $question_number_id (номера вопросов, на которые отвечал пользователь)
	public function getQuestNumberID($subject) {
		echo "тут2 " . count($_SESSION['var']);
		for ($y = 0; $y < count($_SESSION['var']); $y++) { 
			$i = $y +1;
			$question_number_id = $_SESSION['var'][$y]; 
			$maxGameNumber = $this->getMaxLastGameNumber($question_number_id);
			$game_number = $this->getNewGameNumber($question_number_id, $maxGameNumber);
			$user_points = $this->userAnswerCorrectnessControl($subject, $question_number_id, $y);
			$this->add_UserResult_ourQuest_in_db($user_points, $game_number, $i);
		}
		return $game_number;
	}

	public function getMaxLastGameNumber($question_number_id) {			
		//находим максимальный прошлый номер игры
		$sql1 = 'SELECT MAX(game_number) FROM usersAnswers WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$question_number_id.'"'; 
		$sth1 = $this->connect->query($sql1);
		$maxGameNumber = $sth1->fetch(PDO::FETCH_ASSOC);
		return $maxGameNumber;
	}

	public function getNewGameNumber($question_number_id, $maxGameNumber) {	
		//if (!empty($_SESSION['answer'][$y])) { // если ответ пользователя не пустой
		//Рассчитываем номер игры пользователя
		//выбираем строку с номером игры из базы данных
		$sql2 = 'SELECT game_number FROM usersAnswers WHERE user_id="'.$_SESSION['user_id'].'" and question_number_id="'.$question_number_id.'"';
		$sth2 = $this->connect->query($sql2);
		$rowUsersAnswers = $sth2->fetch(PDO::FETCH_ASSOC);
		//если строка с номером игры пустая, то создаем номер игры и записываем его в БД
		if (empty($rowUsersAnswers['game_number'])) {	
			$game_number = 1;
		}
		else { //иначе номер игры = максимальный номер + 1
			$game_number = $maxGameNumber['MAX(game_number)'] + 1;
		}
		return $game_number;
	}	


	public function userAnswerCorrectnessControl($subject, $question_number_id, $y) {
		//проверяем правильность ответа пользователя
		//выбираем правильный ответ на заданный вопрос в базе данных
		$sql3 = 'SELECT answer FROM questions WHERE question_number_id="'.$question_number_id.'" and theme="'.$subject.'"'; 
		$sth3 = $this->connect->query($sql3);
		$rowAnswer = $sth3->fetch(PDO::FETCH_ASSOC); 
		
		$save_answer = $_SESSION['answer'][$y]; //достаем ответ пользователя на вопрос из $_SESSION['answer'][$y]
		$save_answer = mb_strtolower($save_answer,'UTF-8'); //преобразуем ответ пользователя в строчные буквы
		$save_answer = trim($save_answer); //убираем пробелы в конце и в начале ответа пользователя
		if ($rowAnswer['answer'] == $save_answer) { //если ответ пользователя правильный
			$sql4 = 'SELECT points FROM questions WHERE question_number_id="'.$question_number_id.'"';
			$sth4 = $this->connect->query($sql4);
			$user_points_row = $sth4->fetch(PDO::FETCH_ASSOC);//получаем из базы данных количество очков за правильный ответ
			$user_points = $user_points_row['points'];  
			settype($user_points, 'integer');
		}	
		else { //если ответ пользователя неправильный, он получает 0 очков
			$user_points = 0;
		}
		return $user_points;
	}

	public function add_UserResult_ourQuest_in_db($user_points, $game_number, $i) {
		//Добавляем в базу данных количество очков пользователя за ответ на вопрос
		$sql5="INSERT INTO usersAnswers (game_number, user_id, question_number_id, user_points) VALUES (:game_number, :user_id, :question_number_id, :user_points )"; 
		$this->submitDb($sql5, $user_points, $game_number, $i);	
	}

	public function get_UserResult_allQuest($game_number) {
		//Считаем результат игры
		$sql6 = 'SELECT SUM(user_points) FROM usersAnswers WHERE game_number="'.$game_number.'"'. ' AND user_id="'.$_SESSION['user_id'].'"';
		$sth6 = $this->connect->prepare($sql6);
		$sth6->execute();
		$rowAnswer6 = $sth6->fetch(PDO::FETCH_ASSOC); 
		$points = $rowAnswer6['SUM(user_points)'];
		$countRight = $points/10;
		//print('<sqript language="javascript">window.alert("'.$points.'");</sqript>');
		echo "<script language='javascript'> alert('вы набрали ' + ".$points." + ' баллов. Количество правильных ответов: ' + ".$countRight."); </script>";
		return $points;
	}

	public function add_userGameResult_in_db($game_number, $points) {
		//Добавляем результат игры в базу данных
		$sql7="INSERT INTO results (user_id, game_number, theme, points) VALUES (:user_id, :game_number, :theme, :points)"; 
		$prep7 = $this->connect->prepare($sql7);
		$prep7->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
		$prep7->bindValue(':game_number', $game_number, PDO::PARAM_INT);
		$prep7->bindValue(':theme', $_POST['btn_theme'], PDO::PARAM_STR); 
		$prep7->bindValue(':points', $points, PDO::PARAM_INT);
		$arr7 = $prep7->execute(); 
	}

	//обновляем записи с результатами пользователя 
	public function submitDb($sql, $user_points, $game_number, $i) {
		$prep = $this->connect->prepare($sql);
		$prep->bindValue(':game_number', $game_number, PDO::PARAM_INT);
		$prep->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$prep->bindValue(':question_number_id', $_POST['btn_ans'.$i], PDO::PARAM_INT);
		$prep->bindValue(':user_points', $user_points, PDO::PARAM_INT);
		$arr = $prep->execute(); 
		return $arr;
	}
}
$quizModel = new QuizModel($connect);
$subject = $quizModel->showQuestions()[0];
$questionList = $quizModel->showQuestions()[1];
$quizModel->buttonClickControl($subject, $connect);

class QuizView {
	public function showQ($path, $page_name, $connect, $questionList) {
		require_once __DIR__ . "/../src/views/main/template.php";
	}
}
$quizView = new QuizView();
$quizView->showQ($path, $page_name, $connect, $questionList);