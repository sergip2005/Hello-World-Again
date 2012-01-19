<?php

class Basket extends My_Controller {

	function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		$dataController = array();
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