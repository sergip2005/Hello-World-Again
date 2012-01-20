<?php

class Basket extends My_Controller {

	function __construct()
	{
		parent::__construct();
			
	}
	public function exel() {
		$dataController = array();
		$this->load->model('basket_model');
		$basket = $this->basket_model->getBasket();
		$dataController['basket'] = $basket;		
		$output = $this->load->view('pages/basket/exel', $dataController, true);
		echo $output;
	}
	public function index() {	
		$dataController = array();
		$this->load->model('basket_model');
		$basket = $this->basket_model->getBasket();
		$dataController['basket'] = $basket;
		$user = $this->session->all_userdata();
		$dataController['user_id'] =  isset($user['user_id']) ? $user['user_id'] : 0;		
		$data = array(
			'title' 		=> 'Корзина: ' ,
			'description' 	=> '',
			'keywords' 		=> '',			
			'css'			=> array('jquery.tablesorter.blue.css'),
			'js'			=> array('site/parts.js', '/libs/jquery.tablesorter.min.js'),
			'body' 			=> $this->load->view('pages/basket/index', $dataController, true),
		);
		Modules::run('pages/_return_page', $data);
	}
}
?>