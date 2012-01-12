<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Models extends MY_Controller {

	private $_m;

	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->load->model('phones_model');
	}

	public function index()
	{
		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'js'			=> array('js' => 'apanel/regions.js'),
			'body'			=> $this->load->view('pages/models/index', array(), true),
		);
		Modules::run('pages/_return_ap_page', $template);
	}

	public function get_by_vendor($vendor_id)
	{
		$parts = $this->phones_model->getAllVendorModels(intval($vendor_id), 'array');
		if ($parts != false && count($parts) > 0) {
			$this->output->set_output(json_encode(array('status' => 1, 'data' => $parts)));
		} else {
			$this->output->set_output(json_encode(array('status' => 0)));
		}
	}

	public function save()
	{
		$id = intval($this->input->post('id'));
		$name = prepare_phone_name(sanitate_input_string($this->input->post('name')));
		$vendor_id = sanitate_input_string($this->input->post('vendor_id'));
		$data = array('model' => $name, 'vendor_id' => $vendor_id);

		$data['id'] = $this->phones_model->saveModel($id, $data );
		if ($data['id'] > 0) {
			$this->output->set_output(json_encode(array(
					'status'  => 1,
					'item'    => $data,
					'message' => Phones_model::SAVE_SUCCESS
				)));
		}else {
			echo json_encode(array('status' => 0, 'error' => Phones_model::APP_SUBMIT_ERROR));
		}
	}

	public function get()
	{
		$id = intval($this->input->post('id'));
		if ($id > 0) {
			$data = $this->phones_model->get($id);
			$this->output->set_output( json_encode(array(
					'status'  => 1,
					'item'    => $data,
					'message' => Phones_model::SAVE_SUCCESS
				)));
		} else {
			$this->output->set_output(json_encode(array('status' => 0, 'error' => Phones_model::APP_SUBMIT_ERROR)));
		}
	}

	public function remove()
	{
		$id = intval($this->input->post('id'));
		if ($this->phones_model->removeModel($id)) {
			$this->output->set_output(json_encode(array('status' => 1, 'message' => Phones_model::REMOVE_SUCCESS)));
		} else {
			$this->output->set_output(json_encode(array('status' => 0, 'error' => Phones_model::APP_SUBMIT_ERROR)));
		}
	}
}
 
