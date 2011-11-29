<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends MY_Controller {

	public $sheetTypes = array(
			'rev' => 'История ревизий',
			'cabinet' => 'Корпусные элементы',
			'solder' => 'Паечные элементы',
			'prices' => 'Изменения цен',
		);

	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->load->model('import_model');
	}

	public function index()
	{
		$this->load->model('import_model');
		$imports = $this->import_model->get_last_imports_data();

		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'body'			=> $this->load->view('pages/import/index', array('imports' => $imports), true),
		);
		Modules::run('pages/_return_ap_page', $template);
	}

	/**
	 * processes posted file and saves it on server
	 */
	function do_xls_upload()
	{
		$this->load->model('phones_model');
		$this->load->model('vendors_model');
		$this->load->model('regions_model');
		$this->load->model('import_data_tpl_model');

		$config = array(
				'upload_path'	=> $this->config->item('upload_path'),
				'allowed_types' => 'xls|xlsx|pdf|jpg|png|gif',
				'max_size'		=> '0',
			);

		$this->load->library('upload', $config);

		if (! $this->upload->do_upload('file')) {
			$this->session->set_flashdata('message', $this->upload->display_errors());
			redirect('apanel/import/');
		} else {
			$data = $this->_process_xls_upload($this->upload->data());

			$template = array(
				'title'	=> 'Детали импорта',
				'css'	=> array(),
				'js'	=> array('apanel/import/second.js'),
				'body'	=> $this->load->view('pages/import/second_page', $data, true),
			);
			Modules::run('pages/_return_ap_page', $template);
		}
	}

	/**
	 * @param array $data - properties of just uploaded Excel file
	 * @return array - processed & formatted data of posted excel file
	 */
	function _process_xls_upload($data)
	{
		$inputFileName = $data['full_path'];

		$objPHPExcel = $this->import_model->init_phpexcel_object($inputFileName);

		$sheets = array();
		foreach($objPHPExcel->getSheetNames() as $idx => $sheetName) {
			$s = $objPHPExcel->setActiveSheetIndex($idx);
			$sheets[$idx]['id'] = $idx;
			$sheets[$idx]['name'] = $sheetName;
			$sheets[$idx]['cols_number'] = PHPExcel_Cell::columnIndexFromString($s->getHighestColumn());
			$sheets[$idx]['rows_number'] = $s->getHighestRow();
			$sheets[$idx]['demo'] = $this->import_model->getDemoRows($s, $sheets[$idx]['rows_number'], $sheets[$idx]['cols_number']);
		}

		return array(
			'file_data' => $data,
			'sheets' => $sheets,
		);
	}

	function _save_xls_data(){
		$post_data['vendor_id'] = intval($this->input->post('vendors'));
		$post_data['file'] = $this->input->post('file');
		$post_data['sheets'] = $this->input->post('sheets');
		$post_data['model_input'] = $this->input->post('model_input');
		$post_data['model_select'] = intval($this->input->post('model_select'));
		$post_data['sheets_names'] = $this->input->post('sheets_names');
		$post_data['rev_num'] = $this->input->post('rev_num');
		$post_data['rev_desc'] = $this->input->post('rev_desc');

		if (!$post_data['file']) {
			$this->session->set_flashdata('message', 'Не выбран файл для импорта');
			redirect('/apanel/import/');
		}

		// process posted model data
		$existing_data = array();
		if ($post_data['model_select'] > 0) {// if existing model was chosed
			$existing_data['model'] = $this->phones_model->getModelInfo($post_data['model_select']);
		}

		// save import object
		$import['id'] = $this->import_model->save_import_to_temp_table($post_data);

		$objPHPExcel = $this->import_model->init_phpexcel_object($this->config->item('upload_path') . $post_data['file']);

		// process sheets info
		foreach ($post_data['sheets'] as $sheetN => $sheet) {
			$sheets_data = array();
			$sheets_data['id'] = $sheet;
			$sheets_data['type'] = $this->input->post('sheet_type' . $sheet);
			$sheets_data['name'] = $post_data['sheets_names'][$sheetN];

			$sheets_data['col_vals'] = $this->input->post('sheet' . $sheet . '_cols_values');
			$cols = array();
			foreach ($sheets_data['col_vals'] as $n => $v) {
				if ($v !== '0') {
					$cols[$n] = $v;
				}
			}
			$sheets_data['col_vals'] = $cols;
			$sheets_data['col_vals_keys_resseted'] = array_values($cols);
			$sheets_data['fields'] = $this->import_model->get_sheet_fields($sheets_data['type']);
			$sheets_data['vendor_id'] = $post_data['vendor_id'];

			if ($sheets_data['type'] !== '0') {// do not parse and do not save sheets without type provided
				// save all prev data to db as sheet params
				$import['sheet_id'] = $this->import_model->save_sheet_to_temp_table($import['id'], $sheets_data);

				$sheets_data['data'] = $this->import_model->get_sheet_data($objPHPExcel->setActiveSheetIndex($sheet), $sheets_data);
				$this->import_model->save_sheet_data_to_temp_table($import['sheet_id'], $sheets_data['data']);
			}
		}
		return $import['id'];
	}

	function _get_saved_import_data($import_id, $page = 0)
	{
		$return['import_id'] = $import_id;
		$return['post'] = $this->import_model->get_import_from_temp_table($import_id);

		// process sheets info
		$return['sheets'] = $this->import_model->get_sheets_from_temp_table($import_id);

		foreach ($return['sheets'] as $n => $sheet) {
			$return['sheets'][$n]['data'] = $this->import_model->get_sheet_data_from_temp_table($sheet['sheet_id'], $page);
			$return['sheets'][$n]['count_data'] = $this->import_model->get_sheet_data_from_temp_table_count($sheet['sheet_id'], $page);

			// returns array like parts -> code => part props; phone_parts -> code => array of parts
			$return['sheets'][$n]['prev_state'] = $this->phones_model->getPrevDataState(
							array_map('narrow_to_code_field_only', $return['sheets'][$n]['data']),
							$return['post']['vendor_id'],
							isset($return['post']['model_select']) ? $return['post']['model_select'] : false
					);

			if (isset($return['post']['model_select']) && $return['post']['model_select'] > 0) {
				$return['current']['model_parts'] = $this->phones_model->getParts($return['post']['vendor_id'], $return['post']['model_select'], 'all');
				if ($return['current']['model_parts'] !== false) {
					$return['current']['model_parts']['parts'] = process_to_id_keyed_array($return['current']['model_parts']['parts']);
				}
			}
		}
		return $return;
	}

	/**
	 * RECEIVES DATA FROM SECOND PAGE AND FORMS THIRD
	 */
	public function process_details($import_id = 0, $page = 0)
	{
		$this->load->model('vendors_model');
		$this->load->model('phones_model');
		$this->load->model('parts_model');
		$this->load->model('regions_model');
		$this->load->model('currency_model');

		$post = false;
		$import_id = intval($import_id);
		if ($import_id <= 0) {
			$import_id = $this->_save_xls_data();
			$post = true;
		}

		$page = intval($page) - 1;
		$page = $page > 0 ? $page : 0;

		if ($import_id <= 0) {
			$this->session->set_flashdata('message', 'Проблема с обработкой полученных данных<br>Попробуйте произвести импорт ещё раз');
			redirect('/apanel/import/index');
		} elseif ($post) {
			redirect('/apanel/import/process_details/' . $import_id);
		}

		// @XXX there was all logic
		$data = $this->_get_saved_import_data($import_id, $page);

		$template = array(
			'title'	=> 'Подтверждение импорта',
			'css'	=> array(),
			'js'	=> array('apanel/import/third.js'),
			'body'	=> $this->load->view( 'pages/import/third_page', $data, true ),
		);
		Modules::run('pages/_return_ap_page', $template);
	}

	/**
	 * PERFORMS FINAL SAVE OF EDITED DATA
	 */
	public function save(){
		$this->load->model('phones_model');
		$this->load->model('parts_model');
		$this->load->model('currency_model');

		$vendor = intval($this->input->post('vendor'));
		//get new model or existing one
		$model = intval($this->input->post('model_select'));
		$rev_num = $this->input->post('rev_num');
		$rev_desc = $this->input->post('rev_desc');

		if ($model <= 0) {
			$model = $this->input->post('model_input');
			$model = $this->phones_model->getOrCreateModel($model, $vendor);
		} else {
			// remove parts that were not included into new price and checked to be removed
			$to_remove = $this->input->post('parts_to_remove');
			if (count($to_remove) > 0) {
				$this->phones_model->removePhoneParts($model, $to_remove);
			}
		}
		$this->phones_model->saveModel($model, array( 'rev_num' => $rev_num, 'rev_desc' => $rev_desc, 'rev_date' => date('Y-m-d H:i:s') ));

		$sheets = $this->input->post('sheets_data');

		$n = 0;
		foreach ($sheets as $sheet) {
			if (!array_key_exists($sheet['type'], $this->sheetTypes)) continue;
			if (!array_key_exists('rows', $sheet) || count($sheet['rows']) <= 0) continue;

			$model_params = array(
				'type' => $sheet['type'],
				'vendor_id' => $vendor,
				'phone_id' => $model,
			);

			if (in_array($sheet['type'], array('cabinet', 'solder'))) {// import phone parts data
				foreach ($sheet['rows'] as $rowN) {
					if (strlen(r_implode('', $sheet['cols'][$rowN])) <= 0) continue;
					if (!array_key_exists('code', $sheet['cols'][$rowN]) || empty($sheet['cols'][$rowN]['code'])) continue;

					// save or get part id
					$pId = $this->parts_model->updateOrCreate($sheet['cols'][$rowN], $model_params);
					// save or get phone model id
					$ppId = $this->phones_model->updateOrCreatePhonePart($pId, $sheet['cols'][$rowN], $model_params);
					// update phone part regions
					$this->phones_model->updatePartsRegions($ppId, $sheet['cols'][$rowN], $model_params);
					$n += 1;
				}
			} elseif ($sheet['type'] == 'prices') {
				foreach ($sheet['rows'] as $rowN) {
					if (strlen(implode('', $sheet['cols'][$rowN])) <= 0) continue;
					if (!array_key_exists('code', $sheet['cols'][$rowN]) || empty($sheet['cols'][$rowN]['code'])) continue;
					// save or get part id
					$pId = $this->parts_model->updateOrCreate($sheet['cols'][$rowN], $model_params);
					$n += 1;
				}
			}
		}
		// form import message
		$m = 'Записей импортировано: ' . $n;
		$m .= (isset($to_remove) && count($to_remove) > 0) ? '<br>Записей удалено: ' . count($to_remove) : '';
		$this->session->set_flashdata('message', $m);
		redirect('/apanel/import/index');
	}

	public function save_data_template()
	{
		$data['name'] = $this->input->post('name');
		$data['values'] = $this->input->post('values');
		$this->load->model('import_data_tpl_model');
		$this->import_data_tpl_model->save($data);
		$this->output->set_output($this->import_data_tpl_model->getSelect());
	}

	public function remove_data_template($id)
	{
		$this->load->model('import_data_tpl_model');
		$this->import_data_tpl_model->remove(intval($id));
		$this->output->set_output($this->import_data_tpl_model->getSelect());
	}

}