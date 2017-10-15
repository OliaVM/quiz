<?php
class RegView {
	public $regModel;
	public function __construct () {
		$this->regModel = new RegModel;
	}
	//Registration 	
	public function showRegForm($connect) {
		$exRegistration = $this->regModel->regFormSendingControl($connect); //Exception during the registration attempt 
		if (isset($exRegistration)) {
			require __DIR__ . '/../forms/registration_form_save.php';
		}
		else {
			require __DIR__ . '/../forms/registration_form.php';
		}
		// Print the exeption
		echo '<h2 class="redcolor">' . $exRegistration . '</h2>';
	}

	public function generateRegistrationView($connect) {
		$this->showRegForm($connect);
		echo "<p><a href='/index.php'>Перейти на главную страницу</a></p>";
	}

}
