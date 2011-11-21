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
			'js'	=> array('/apanel/parts/index.js'),
			'body'	=> $this->load->view('pages/parts/index', $data, true),
		);

		Modules::run('pages/_return_ap_page', $template);
	}

	public function search(){
		$this->load->model('phones_model');

		$search_params['vendor_id'] = (int)$this->input->post('vendor_id');
		$search_params['model_id'] = $this->input->post('model_id');
		if (!in_array($search_params['model_id'], array('all', 'none'))) {
			$search_params['model_id'] = intval($search_params['model_id']);
		}
		if ($search_params['model_id'] <= 0) {
			$this->output->set_output(json_encode(array(
					'status' => 0
				)));
			return;
		}
		$parts = $this->phones_model->getParts($search_params['vendor_id'], $search_params['model_id'], 'all');
		$this->output->set_output(json_encode(array(
				'status' => 1,
				'data' => $parts
			)));
	}
}