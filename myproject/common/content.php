		
	<div class="row-fluid">
	    <div class="span10" id="box2">
	    	<ul>
				<li><a href="/index.php">Главная</a></li>
				<?php if (!isset($_SESSION['login'])): ?>
				<li><a href="http://myproject.local/avtorization/entrance_page.php">Авторизуйтесь или зарегистрируйтесь, чтобы начать викторину</a></li>
				<?php endif; ?>
				<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
					<h2>Выберите тему викторины: </h2>
					<li><a href="http://myproject.local/index.php?subject_go=литература">Литература</a></li>
					<li><a href="http://myproject.local/index.php?subject_go=история">История</a></li>
					<!-- <li>Лучшие результаты</li> -->
				<?php endif; ?>
			</ul> 
		</div>
	</div>
	
	<!-- 
	<form method="post">
							Выберите тему викторины: <br>
							<SELECT name = "subject">
								<OPTION value = "литература">Литература 
								<OPTION value = "история">История 
								<OPTION value = "mathematics">Математика 
							</SELECT> 
							<br>
							<input type="submit" name="quiz_theme_go" value="выбрать">
	</form>
	-->
	<?php //if(isset($_POST['quiz_theme_go'])): ?>
	<!-- <li><a href="<?php //echo "http://myproject.local/index.php?subject_go=" . $subject; ?>"><?php //echo $str; ?></a></li> -->
	<?php //endif; ?>

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
			
		</div>
	</div>





