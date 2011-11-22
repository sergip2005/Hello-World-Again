<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parts extends My_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('parts_model');
		$this->load->model('phones_model');
	}

	public function index($number)
	{
		$number = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $number);
		$parts  = $this->parts_model->getPartsByNumber($number);
		$data   = array(
			'title' 		=> 'Номер: ' . $number,
			'description' 	=> '',
			'keywords' 		=> '',
			'body' 			=> $this->load->view('pages/parts/index', array('parts' => $parts), true),
		);
		Modules::run('pages/_return_page', $data);
	}
	public function search($query, $parameter = '')
	{
		$q = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $query);
		$p = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $parameter);
		$catalog  = $this->phones_model->getAllParts();
		$parts  = $this->parts_model->searchParts($q, $p);
		$data = array(
			'title' 		=> 'Поиск',
			'description' 	=> '',
			'keywords' 		=> '',
			'body' 			=> $this->load->view('pages/parts/search', array('parts' => $parts, 'catalog' => $catalog), true),
		);
		Modules::run('pages/_return_page', $data);
	}
}