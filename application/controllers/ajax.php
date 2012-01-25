<?php
class Ajax extends My_Controller {
	
	function __construct(){
		parent::__construct();	
		$action = $this->uri->segments;
		$action = $action[2];		
		$this->proccessActions($action);
		exit;
	}

	private function proccessActions($action){
		$action = 'action'.$action;
		if (method_exists($this, $action)) {
			$this->$action();
			return true;
		} else {
			return false;
		}
	}
	
	private function actionInsertIntoBasket() {
		$this->load->model('basket_model');
		$this->basket_model->InsertIntoBasket();
		
	}

}
?>