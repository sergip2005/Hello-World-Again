<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
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
		$config = array(
				'upload_path'	=> BASEPATH . '../assets/.uploads/',
				'allowed_types' => 'xls|xlsx|pdf|jpg|png|gif',
				'max_size'		=> '0',
			);

		$this->load->library('upload', $config);

		if (! $this->upload->do_upload('file')) {
			$this->session->set_flashdata('message', $this->upload->display_errors());
			redirect('apanel/import');
		} else {
			$this->_process_xls_upload($this->upload->data());
		}
	}

	function _process_xls_upload($data)
	{
		$this->load->library('PHPExcel');

		$inputFileName = $data['full_path'];

		// Identify the type of $inputFileName
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		// Create a new Reader of the type that has been identified
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		// Load $inputFileName to a PHPExcel Object
		//$objPHPExcel = $objReader->load($inputFileName);
		try {
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file: '.$e->getMessage());
		}

		/* Process file data
		$data =  array();
		$worksheet = $objPHPExcel->getActiveSheet();
		foreach ($worksheet->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			foreach ($cellIterator as $cell) {
				$data[$cell->getRow()][$cell->getColumn()] = $cell->getValue();
			}
		}*/

		$sheets = array();
		foreach($objPHPExcel->getSheetNames() as $idx => $sheetName) {
			$sheets[$idx] = $sheetName;
		}

		$tpl_data = array(
			'sheets' => $sheets
		);
		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'css'			=> array(),
			'js'			=> array(),
			'body'			=> $this->load->view('pages/import/second_page', $tpl_data, true),
		);
		Modules::run('pages/_return_ap_page', $template);

	}
}