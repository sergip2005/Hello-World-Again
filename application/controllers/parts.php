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
		$number = sanitate_input_string(urldecode($number));
		$parts = $this->parts_model->getPartsByCode($number);
		$data = array(
			'title' 		=> 'Код: ' . urldecode($number),
			'description' 	=> '',
			'keywords' 		=> '',
			'css'			=> array('jquery.tablesorter.blue.css'),
			'js'			=> array('site/parts.js', '/libs/jquery.tablesorter.min.js'),
			'body' 			=> $this->load->view('pages/parts/index', array('parts' => $parts), true),
		);
		Modules::run('pages/_return_page', $data);
	}

	public function search($parameter, $query)
	{
		$this->load->model('currency_model');

		$search_params['query'] = sanitate_input_string(urldecode($query));
		$search_params['parameter'] = sanitate_input_string($parameter);
		$search_params['pagination']['page'] = get_posted_page($this->input->post('page'));
		$parts = $this->parts_model->searchParts($search_params['query'], $search_params['parameter'], $search_params['pagination']['page']);
		calculatePaginationParams($search_params['pagination']);
		$data = array(
			'title' 		=> 'Поиск',
			'description' 	=> '',
			'keywords' 		=> '',
			'css'			=> array('jquery.tablesorter.blue.css'),
			'js'			=> array('site/search.js', '/libs/jquery.tablesorter.min.js'),
			'body' 			=> $this->load->view('pages/parts/search', array('parts' => $parts, 'search_params' => $search_params), true),
		);
		Modules::run('pages/_return_page', $data);
	}
}