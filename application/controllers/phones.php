<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones extends My_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('phones_model');
	}

	public function index()
	{
		$catalog  = $this->phones_model->getAllParts();
		$data = array(
			'title' 		=> 'Главная страница',
			'description' 	=> '',
			'keywords' 		=> '',
			'body' 			=> $this->load->view('pages/phones/index', array('catalog' => $catalog), true),
		);
		Modules::run('pages/_return_page', $data);
	}

	public function parts($vendor, $model, $region = '')
	{
		$vendor = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $vendor);
		$model  = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $model);
		$region = $region == 'all' ? $region : '';
		$catalog  = $this->phones_model->getAllParts();
		$parts = $this->phones_model->getParts($vendor, str_replace('_', ' ', $model), $region);
		$data = array(
			'title' 		=> 'Раскладка ' . $vendor . ' ' . $model,
			'js'			=> array('libs/jquery.jqzoom-core-pack.js', 'site/phones.js'),
			'css'			=> array('jquery.jqzoom.css'),
			'description' 	=> $vendor . ', ' . $model,
			'keywords' 		=> $vendor . ', ' . $model,
			'body' 			=> $this->load->view('pages/phones/parts', array('parts'   => $parts,
																			 'catalog' => $catalog,
																			 'region'  => $region,
																			 'vendor'  => $vendor,
																			 'model'   => $model),
												 true),
		);
		Modules::run('pages/_return_page', $data);
	}
}