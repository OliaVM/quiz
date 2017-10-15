<?php
class ExitController {
	public $exitModel;
	public $exitView;
	public function __construct() {
		$this->exitModel = new ExitModel();
		$this->exitView = new ExitView();
	}
	public function actionExit() {
		$this->exitView->show_button_exit();
		$this->exitModel->do_exit();
	}
}

