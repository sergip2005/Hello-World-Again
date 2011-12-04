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
		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'body'			=> $this->load->view('pages/import/index', '', true),
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

	/**
	 * RECEIVES DATA FROM SECOND PAGE AND FORMS THIRD
	 */
	public function process_details()
	{
		$this->load->model('vendors_model');
		$this->load->model('phones_model');
		$this->load->model('parts_model');
		$this->load->model('regions_model');
		$this->load->model('currency_model');

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

		$objPHPExcel = $this->import_model->init_phpexcel_object($this->config->item('upload_path') . $post_data['file']);

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
			$sheets_data[$sheet]['fields'] = $this->import_model->get_sheet_fields($sheets_data[$sheet]['type']);

			$sheets_data[$sheet]['vendor_id'] = $post_data['vendor_id'];

			if ($sheets_data[$sheet]['type'] !== '0') {// do not parse sheets without type provided
				$sheets_data[$sheet]['data'] = $this->import_model->get_sheet_data($objPHPExcel->setActiveSheetIndex($sheet), $sheets_data[$sheet]);
				/* returns array like
				 * parts -> code => part props;
				 * phone_parts -> code => array of parts
				 */
				if ($sheets_data[$sheet]['type'] == 'prices') {
					$sheets_data[$sheet]['prev_state'] = $this->phones_model->getParts($post_data['vendor_id'], 'all', 'all');
					$sheets_data[$sheet]['prev_state']['parts'] = count($sheets_data[$sheet]['prev_state']['parts']) > 0 ? process_to_code_keyed_array($sheets_data[$sheet]['prev_state']['parts']) : array();
				} else {
					$sheets_data[$sheet]['prev_state'] = $this->phones_model->getPrevDataState(array_map('narrow_to_code_field_only', $sheets_data[$sheet]['data']), $post_data['vendor_id'], isset($existing_data['model']) ? $existing_data['model']['id'] : false);
				}
			} else {
				continue;
			}

			if (isset($existing_data['model'])) {// if this is parts import
				$existing_data['model_parts'] = $this->phones_model->getParts($post_data['vendor_id'], $existing_data['model']['id'], 'all');
				if ($existing_data['model_parts'] !== false) {
					$existing_data['model_parts']['parts'] = process_to_id_keyed_array($existing_data['model_parts']['parts']);
				}
				//echo '<pre style="text-align: left">' . print_r($existing_data['model_parts'], true) . '</pre>';
			}

			if ($sheets_data[$sheet]['type'] == 'prices') {// save prices data
				$n = array('existing' => 0, 'new' => 0, 'excluded' => 0);//number of parts processed
				$data = array('existing' => array(), 'new' => array(), 'excluded' => array());
				foreach ($sheets_data[$sheet]['data'] as $row) {// for every row in price list
					if (strlen(implode('', $row)) <= 0) continue;
					if (!array_key_exists('code', $row) || empty($row['code'])) continue;

					if (array_key_exists($row['code'], $sheets_data[$sheet]['prev_state']['parts'])) {
						$n['existing'] += 1;
						$data['existing'][$row['code']] = $sheets_data[$sheet]['prev_state']['parts'][$row['code']];
						// remove updated part from array
						unset($sheets_data[$sheet]['prev_state']['parts'][$row['code']]);
					} else {
						$n['new'] += 1;
						$data['new'][$row['code']] = $row;
					}

					// save or get part id
					$this->parts_model->updateOrCreate($row, array('vendor_id' => $sheets_data[$sheet]['vendor_id']));
				}
				if (!empty($sheets_data[$sheet]['prev_state']['parts']) && count($sheets_data[$sheet]['prev_state']['parts']) > 0) {
					$data['excluded'] = $sheets_data[$sheet]['prev_state']['parts'];
					foreach ($sheets_data[$sheet]['prev_state']['parts'] as $part) {
						$n['excluded'] += 1;
						// update part price to 0.00
						$this->parts_model->save($part['id'], array('price' => 0));
					}
				}
				$sheets_data[$sheet]['results'] = $this->_show_import_results($n, $data);
			}
		}

		$template = array(
			'title'	=> 'Подтверждение импорта',
			'css'	=> array(),
			'js'	=> array('apanel/import/third.js'),
			'body'	=> $this->load->view('pages/import/third_page', array('sheets' => $sheets_data, 'post' => $post_data, 'current' => $existing_data), true),
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
			if (!empty($to_remove) && count($to_remove) > 0) {
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
			} elseif ($sheet['type'] == 'prices') {// moved to previous step
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
		if (!empty($to_remove) && count($to_remove) > 0) die(print_r(array($to_remove), true));
		$m .= (!empty($to_remove) && count($to_remove) > 0) ? '<br>Записей удалено: ' . count($to_remove) : '';
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

	private function _show_import_results($n, $parts)
	{
		return $this->load->view('pages/import/import_price_results', array('parts' => $parts, 'n' => $n), true);
	}

}