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

	public function parts($vendor, $model, $page = 0, $region = '')
	{
		$this->load->model('regions_model');
		$this->load->model('currency_model');
		$this->load->model('vendors_model');

		$model = explode('/', $_SERVER['REQUEST_URI']);
		$model = $model[3];
		$debug = array(explode('/', $_SERVER['REQUEST_URI']));
		$vendor = sanitate_input_string($vendor);
		$model  = sanitate_input_string($model);
		$vendor_obj = $this->vendors_model->getByName($vendor);
		$this->vendor_obj = $vendor_obj;
		$default_region = $region == '' ? $this->regions_model->getDefault() : false;
		$region = $region == 'all' ? $region : '';
		if ($model == 'none')
		{
			$model_id = 'none';
			$search_params['pagination']['page'] = get_posted_page($page);
			$search_params['vendor'] = $vendor;
			$search_params['pagination']['items'] = $this->phones_model->countGetParts($vendor_obj['id'], $model_id, 'all');
			$parts = $this->phones_model->getParts($vendor_obj['id'], $model_id, 'all', false, $search_params['pagination']['page']);
			calculatePaginationParams($search_params['pagination']);
			$view = 'pages/parts/unsorted';
			$model_obj = array(
				'id' => 0,
				'vendor_id' => $vendor,
				'model' => '',
				'image' => '',
				'solder_image' => '',
				'cabinet_image' => '',
				'rev_num' => '',
				'rev_desc' => '',
				'rev_date' => '',
			);
		}
		else
		{
			$model_obj = $this->phones_model->getModelByName(prepare_phone_name($model));
			$view = 'pages/phones/parts';
			$parts = $this->phones_model->getParts($vendor_obj['id'], $model_obj['id'], $default_region === false ? 'all' : $default_region, false, 0);
			$search_params = '';
		}
		$this->model_obj = $model_obj;
		$data = array(
			'title'			=> 'Раскладка деталей для телефона ' . $vendor_obj['name'] . ' модель ' . $model_obj['model'],
			'js'			=> array('libs/jquery.jqzoom-core-pack.js', '/libs/jquery.metadata.js', '/libs/jquery.tablesorter.min.js', 'site/phones.js'),
			'css'			=> array('jquery.jqzoom.css', 'jquery.tablesorter.blue.css'),
			'description'	=> $vendor_obj['name'] . (isset($model_obj) ? ', ' . $model_obj['model'] : ''),
			'keywords'		=> $vendor_obj['name'] . (isset($model_obj) ? ', ' . $model_obj['model'] : ''),
			'body'			=> $this->load->view(
									$view,
									array(
										'parts' => $parts,
										'region' => $region,
										'vendor' => $vendor_obj,
										'model' => $model_obj,
										'default_region' => $default_region,
										'search_params' => $search_params,
										//'debug' => $debug,
									),
									true),
			'data'			=> array('model' => $model_obj, 'vendor' => $vendor_obj),
		);
		Modules::run('pages/_return_page', $data);
	}

	public function vendor($vendor)
	{
		$vendor = sanitate_input_string($vendor);
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