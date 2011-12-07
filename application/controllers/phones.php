<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones extends My_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('phones_model');
	}

	public function index()
	{
		$this->load->module('pages');
		$this->load->model('pages_m');
		$page = $this->pages_m->get_page('index');

		$data = array(
			'title' 		=> $page['title'],
			'description' 	=> $page['description'],
			'keywords' 		=> $page['keywords'],
			'body' 			=> $this->load->view(
										'pages/phones/index',
										array(
											'body' => $page['body']
										), true),
		);
		Modules::run('pages/_return_page', $data);
	}

	public function parts($vendor, $model, $region = '')
	{
		$this->load->model('regions_model');
		$this->load->model('currency_model');

		$vendor = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $vendor);
		$model  = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $model);
		$region = $region == 'all' ? $region : '';
		$default_region = $region == '' ? $this->regions_model->getDefault() : false;
		$data = array(
			'title'			=> 'Раскладка ' . $vendor . ' ' . $model,
			'js'			=> array('libs/jquery.jqzoom-core-pack.js', '/libs/jquery.metadata.js', '/libs/jquery.tablesorter.min.js', 'site/phones.js'),
			'css'			=> array('jquery.jqzoom.css', 'jquery.tablesorter.blue.css'),
			'description'	=> $vendor . ', ' . $model,
			'keywords'		=> $vendor . ', ' . $model,
			'body'			=> $this->load->view(
									'pages/phones/parts',
									array(
										'parts' => $this->phones_model->getPartsByName($vendor, str_replace('_', ' ', $model), $region),
										'region' => $region,
										'vendor' => $vendor,
										'model' => $model,
										'default_region' => $default_region,
									),
									true),
		);
		Modules::run('pages/_return_page', $data);
	}

	public function vendor($vendor)
	{
		$vendor = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $vendor);
		$models = $this->phones_model->getVendorModels($vendor);
		$data = array(
			'title' 		=> 'Производитель: ' . $vendor ,
			'description' 	=> $vendor ,
			'keywords' 		=> $vendor ,
			'body' 			=> $this->load->view('pages/phones/vendor', array('vendor' => $vendor, 'models' => $models), true),
		);
		Modules::run('pages/_return_page', $data);
	}
}