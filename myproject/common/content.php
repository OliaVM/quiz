		
	<div class="row-fluid">
	    <div class="span10" id="box2">
	    	<ul>
				<li><a href="/victorina.php">Главная</a></li>
				<li>Начать викторину</li>
					<ul><li><a href="http://myproject.local/avtorization/avtorization_page.php">Авторизация</a></li>
					<li><a href="http://myproject.local/avtorization/registration_page.php">Регистрация</a></li></ul>
				<li>Лучшие результаты</li>
			</ul>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10" id="box4">
			<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
				<!-- Вывод записей на экран -->
				<p><?php $points = 0; ?></p>
				<?php foreach ($notes as $row): ?>
				<br> 
				<p><?php $points= $row['points'] + $points ; ?></p>
				<p><?php echo $row['question_number_id']; ?></p>
				<p><?php echo $row['question']; ?></p>
				<p><h2><?php //echo $row['answer']; ?></h2></p>
				
				<form method="post">
					<input type='hidden' name="red_id" value="<?php echo $row['id']; ?>">
					<input type="text" name="answer_from_form">
					<input type="submit" name="go" value="ответить">
					<a href='http://myproject.local/avtorization/avtorization_page.php?question_number_id=<?php echo $row['question_number_id']; ?>'>Ответить </a>
				</form>
				<?php endforeach; ?>
				<p><?php echo $points; ?></p>
			<?php endif; ?>
		</div>
	</div>




