					<?php if (!isset($_SESSION['password'])): ?>
						<div align="center"><h2>Регистрация на сайте:</h2>
							<form  method="post">
								Логин: <input type="text" name="login2" size="20" maxlength="22"><br>
								Пароль: <input type="password" name="password2" size="20" maxlength="22"><br><br>
								Имя: <input type="text" name="name" size="20" maxlength="22"><br>
								Фамилия: <input type="text" name="surname" size="20" maxlength="22"><br>
								День рождения в формате год-месяц-день: <input type="text" name="date_of_birth" size="10" maxlength="12"><br>
								Пол: 
								<SELECT name = "gender">
									<OPTION value = "woman">woman
									<OPTION value = "man">man
								</SELECT><br>
								email: <input type="email" name="email2"><br>
								<input type="submit" name="submit2">
							</form>
						</div>
					<?php else: echo "Вы уже авторизованы"; ?>
					<?php endif; ?>
					<div>




