<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends MY_Controller {
	private $_m;

	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->_m = $this->load->model('import_model');
	}

	public function index()
	{
		$vendors = $this->load->model('vendors_model');

		$data = array(
			'vendors_select' => $vendors->getAll('select')
		);

		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'body'			=> $this->load->view('pages/import/index', $data, true),
		);
		Modules::run('pages/_return_ap_page', $template);
	}

	public function import_details()
	{
		
	}

	function do_xls_upload()
	{
		$this->load->model('phones_model');

		$config = array(
				'upload_path'	=> $this->config->item('upload_path'),
				'allowed_types' => 'xls|xlsx|pdf|jpg|png|gif',
				'max_size'		=> '0',
			);

		$this->load->library('upload', $config);

		if (! $this->upload->do_upload('file')) {
			$this->session->set_flashdata('message', $this->upload->display_errors());
			redirect('apanel/import');
		} else {
			$data = $this->_process_xls_upload($this->upload->data());

			$mVendors = $this->load->model('vendors_model');
			$data['vendors_select'] = $mVendors->getAll('select');

			$template = array(
				'title'	=> 'Детали импорта',
				'css'	=> array(),
				'js'	=> array('apanel/import/details.js'),
				'body'	=> $this->load->view('pages/import/second_page', $data, true),
			);
			Modules::run('pages/_return_ap_page', $template);
		}
	}

	function _process_xls_upload($data)
	{
		$inputFileName = $data['full_path'];

		$objPHPExcel = $this->_m->init_phpexcel_object($inputFileName);

		$sheets = array();
		foreach($objPHPExcel->getSheetNames() as $idx => $sheetName) {
			$s = $objPHPExcel->setActiveSheetIndex($idx);
			$sheets[$idx]['id'] = $idx;
			$sheets[$idx]['name'] = $sheetName;
			$sheets[$idx]['cols_number'] = PHPExcel_Cell::columnIndexFromString($s->getHighestColumn());
			$sheets[$idx]['rows_number'] = $s->getHighestRow();
			$sheets[$idx]['demo'] = $this->_m->getDemoRows($s, $sheets[$idx]['rows_number'], $sheets[$idx]['cols_number']);
		}

		return array(
			'file_data' => $data,
			'sheets' => $sheets,
		);
	}

	public function process_details()
	{
		$post_data['vendor_id'] = intval($this->input->post('vendors'));
		$post_data['file'] = $this->input->post('file');
		$post_data['sheets'] = $this->input->post('sheets');
		$post_data['model_input'] = $this->input->post('model_input');
		$post_data['model_select'] = $this->input->post('model_select');
		$post_data['sheets_names'] = $this->input->post('sheets_names');

		$mVendors = $this->load->model('vendors_model');
		$post_data['vendors_select'] = $mVendors->getAll('select', array('selected' => $post_data['vendor_id']));

		$objPHPExcel = $this->_m->init_phpexcel_object($this->config->item('upload_path') . $post_data['file']);

		// process sheets info
		$sheets_data = array();
		foreach ($post_data['sheets'] as $sheetN => $sheet) {
			$sheets_data[$sheet]['id'] = $sheet;
			$sheets_data[$sheet]['type'] = $this->input->post('sheet_type' . $sheet);
			$sheets_data[$sheet]['name'] = $post_data['sheets_names'][$sheetN];

			$sheets_data[$sheet]['col_vals'] = $this->input->post('sheet' . $sheet . '_cols_values');
			$cols = array();
			foreach ($sheets_data[$sheet]['col_vals'] as $n => $v) {
				if ($v !== '0') {
					$cols[$n] = $v;
				}
			}
			$sheets_data[$sheet]['col_vals'] = $cols;
			$sheets_data[$sheet]['col_vals_keys_resseted'] = array_values($cols);
			$sheets_data[$sheet]['fields'] = $this->_m->get_sheet_fields($sheets_data[$sheet]['type']);

			$sheets_data[$sheet]['vendor_id'] = $post_data['vendor_id'];

			if ($sheets_data[$sheet]['type'] !== '0') {// do not parse sheets without type provided
				$sheets_data[$sheet]['data'] = $this->_m->get_sheet_data($objPHPExcel->setActiveSheetIndex($sheet), $sheets_data[$sheet]);
			}
		}

		//$this->output->set_output('<pre>' . print_r($sheets_data, TRUE));
		$template = array(
			'title'	=> 'Подтверждение импорта',
			'css'	=> array(),
			'js'	=> array('apanel/import/details.js'),
			'body'	=> $this->load->view('pages/import/third_page', array('sheets' => $sheets_data, 'import' => $post_data), true),
		);
		Modules::run('pages/_return_ap_page', $template);
	}

	public function save(){
		$sheets = $this->input->post('sheets_data');
		$vendor = intval($this->input->post('vendor'));

		$this->output->set_output('<pre>' . print_r($_POST, true) . '</pre>');
	}
}