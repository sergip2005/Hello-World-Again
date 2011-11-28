<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parts extends MY_Controller {

	private $_m;

	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->_m = $this->load->model('parts_model');
	}

	public function index()
	{
		$this->load->model('vendors_model');
		$this->load->model('phones_model');

		$data = array(
			'vendors' => $this->vendors_model->getAll()
		);

		$template = array(
			'title'	=> 'Запчасти',
			'css'	=> array(),
			'js'	=> array('apanel/parts/index.js'),
			'body'	=> $this->load->view('pages/parts/index', $data, true),
		);

		Modules::run('pages/_return_ap_page', $template);
	}

	public function search(){
		$this->load->model('phones_model');
		$this->load->model('parts_model');

		$search_params['vendor_id'] = (int)$this->input->post('vendor_id');
		$search_params['model_id'] = $this->input->post('model_id');

		$search_params['query'] = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', urldecode($this->input->post('query')));
		$search_params['parameter'] = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $this->input->post('param'));

		if (!in_array($search_params['model_id'], array('all', 'none'))) {
			$search_params['model_id'] = intval($search_params['model_id']);

			if ($search_params['model_id'] <= 0) {
				if (isset($search_params['query']) && isset($search_params['parameter'])) {

					switch ($search_params['parameter']){
						case 'parts_code':
							$parts = $this->parts_model->getPartsByNumber($search_params['query']);
						break;
						case 'model_name':
							$parts = $this->parts_model->searchParts($search_params['query'], 'models');
						break;
						case 'parts_name':
							$parts = $this->parts_model->searchParts($search_params['query'], 'part_name');
						break;
					}
					if (count($parts) > 0) {
						$this->output->set_output(json_encode(array( 'status' => 1, 'data' => array('parts' => $parts))));
						return;
					} else {
						$this->output->set_output(json_encode(array( 'status' => 0 )));
						return;
					}

				}else {
					$this->output->set_output(json_encode(array( 'status' => 0 )));
					return;
				}
			}

		}
		$parts = $this->phones_model->getParts($search_params['vendor_id'], $search_params['model_id'], 'all');
		if ($parts !== false && count($parts) > 0) {
			$this->output->set_output(json_encode(array( 'status' => 1, 'data' => $parts )));
		} else {
			$this->output->set_output(json_encode(array( 'status' => 0 )));
		}
	}
}