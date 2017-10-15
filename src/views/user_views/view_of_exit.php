<?php
class ExitView {
	public function show_button_exit() {
		if (isset($_SESSION['login'])) {
			//Display the exit button from the session 
			require_once __DIR__ . '/../forms/exit_button.php'; 
		}
	}
}




