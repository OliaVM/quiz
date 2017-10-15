		
	
	    	<ul>
				<li><a href="/index.php">Главная</a></li>
				<?php if (!isset($_SESSION['login'])): ?>
					<li><a href="/index.php?page_name=entrance_page">Авторизуйтесь или зарегистрируйтесь, чтобы начать викторину</a></li>
				<?php endif; ?>
				<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
					<h2>Выберите тему викторины: </h2>
					<li><a href="/index.php?subject_go=литература">Литература</a></li>
					<li><a href="/index.php?subject_go=история">История</a></li>
					<!-- <li>Лучшие результаты</li> -->
				<?php endif; ?>
			</ul> 
		</div>
	</div>
	
	<!-- Выход -->
	<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
		<div class="row-fluid">
		    <div class="span10" id="box3">
				<?php $exitController = new ExitController(); ?>
				<?php $exitController->actionExit(); ?>
			</div>
		</div>
	<?php endif; ?>	

	<!-- Вывод вопросов из базы данных на экран -->
	<div class="row-fluid">
		<div class="span10" id="box4">
			<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
				<?php $z = 1; ?>
				<?php $_SESSION['var'] = []; ?>
				<!-- Если пользователь выбрал тему викторины, показываем вопросы -->
				<?php if (isset($_GET['subject_go'])): ?>
					<form method="post">				
						<?php foreach ($questionList as $row): ?>
								<p><?php echo $row['question_number_id']; ?></p>
								<p><?php echo $row['question']; ?></p>
								<input type='hidden' value="<?php echo $row['theme']; ?>" name="btn_theme"> <!--тема викторины -->
								<input type='text' name="<?php echo "answer_from_form".$z; ?>" >
								<input type='hidden' value="<?php echo $row['question_number_id']; ?>" name="<?php echo "btn_ans".$z; ?>" >  <!--номер вопроса -->
								<?php $z = $z + 1; ?>
						<?php endforeach; ?>
						<br><input type="submit" name="button" value="ответить"><br> 
					</form>
				<?php endif; ?>	
			<?php endif; ?>






