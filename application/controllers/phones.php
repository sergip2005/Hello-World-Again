<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones extends My_Controller {

	public function index()
	{
		$data = array(
			'title' 		=> 'Главная страница',
			'description' 	=> '',
			'keywords' 		=> '',
			'body' 			=> $this->load->view('pages/phones/index', '', true),
		);
		Modules::run('pages/_return_page', $data);
	}
}