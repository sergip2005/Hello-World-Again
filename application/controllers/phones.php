<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones extends My_Controller {

	public function index()
	{
		$data = array(
			'title' => 'Главная страница',
			'description' => '',
			'keywords' => '',
			'top_menu' => $this->load->view('partials/top_menu', '', true),
			'user_menu' => $this->load->view('partials/user_menu', '', true),
			'body' => $this->load->view('phones/index', '', true),
			'bottom_menu' => $this->load->view('partials/bottom_menu', '', true),
		);
		Modules::run('pages/_return_page', $data);
	}
}