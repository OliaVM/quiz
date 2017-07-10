		
	<div class="row-fluid">
	    <div class="span10" id="box2">
	    	<ul>
				<li><a href="/index.php">Главная</a></li>
				<?php if (!isset($_SESSION['login'])): ?>
				<li><a href="http://myproject.local/avtorization/entrance_page.php">Авторизуйтесь или зарегистрируйтесь, чтобы начать викторину</a></li>
				<?php endif; ?>
				<?php //if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
					<h2>Выберите тему викторины: </h2>
					<li><a href="http://myproject.local/index.php?subject_go=литература">Литература</a></li>
					<li><a href="http://myproject.local/index.php?subject_go=история">История</a></li>
					<!-- <li>Лучшие результаты</li> -->
				<?php //endif; ?>
			</ul> 
		</div>
	</div>

	<!-- Выход -->
	<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
		<div class="row-fluid">
		    <div class="span10" id="box2">
				<?php require_once '/var/www/html/src/core/form/exit_button.php'; ?>
			</div>
		</div>
	<?php endif; ?>	

	<!-- Вывод вопросов из базы данных на экран -->
	<div class="row-fluid">
		<div class="span10" id="box4">
			<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
				<?php $z = 1; ?>
				<?php $_SESSION['var'] = []; ?>
				<?php if (isset($_GET['subject_go'])): ?>
				<form method="post">
					<!-- Вывод записей на экран -->
					
						<?php foreach ($questionList as $row): ?>
							<br> 
							<p><?php //$points= $row['points'] + $points ; ?></p>
							<p><?php echo $row['question_number_id']; ?></p>
							<p><?php echo $row['question']; ?></p>
							<p><h2><?php //echo $row['answer']; ?></h2></p>
								<input type='hidden' value="<?php echo $row['theme']; ?>" name="<?php echo "btn_theme"; ?>" > 
								<input type='text' name="<?php echo "answer_from_form".$z; ?>" >
								<input type='hidden' value="<?php echo $row['question_number_id']; ?>" name="<?php echo "btn_ans".$z; ?>" > 
								<?php //$_SESSION['var'] = $_POST['btn_ans'.$i]; ?>	
							<?php echo "z ". $z; ?>	
							<?php $z = $z + 1; ?>
						<?php endforeach; ?>
					
					<br><input type="submit"  name="button" value="ответить">
				</form>
				<?php endif; ?>	
				<p><?php //echo $points; ?></p>
			<?php endif; ?>
		</div>
	</div>




