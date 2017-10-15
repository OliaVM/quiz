<?php
class AuthController {
	public $model;
	public $view;
	public function __construct() {
		$this->model = new AuthClassModel();
		$this->view = new AuthClassView();
	}
	public function actionAuthoriz($connect) {
		$this->model->formSendingControl($connect);
		$this->view->generateAuthorizationView($connect);
	}
}

