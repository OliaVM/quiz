<?php
class RegController {
	public $model;
	public $view;
	public function __construct() {
		$this->model = new RegModel();
		$this->view = new RegView();
	}
	public function actionReg($connect) {
		$this->model->regFormSendingControl($connect);
		$this->view->generateRegistrationView($connect);
	}
}

