<?php require_once '/var/www/html/myproject/common/header.php'; ?> 	
<?php //if (!isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
	<li><a href="http://myproject.local/avtorization/avtorization_page.php">Авторизация</a></li>
	<li><a href="http://myproject.local/avtorization/registration_page.php">Регистрация</a></li></ul>
<?php //endif; ?>	
<li><a href="/index.php">Главная</a></li>

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
<!-- <li><a href="<?php echo "http://myproject.local/index.php?subject_go=" . $subject; ?>"><?php echo $str; ?></a></li> -->
<?php //endif; ?>
			

<?php require_once '/var/www/html/myproject/common/footer.php'; ?> 

