<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_model extends CI_Model
{
	private $_worksheet;
	private $_row;
	public $per_page = 20;

	public $part_field_types = array(
			'name'		=> 'Ориг. имя детали',
			'name_rus'	=> 'Рус. имя детали',
			'code'		=> 'Парт. номер детали',
			'cct_ref'	=> 'Позиция детали на рисунке',
			'ptype'		=> 'Тип детали',
			'num'		=> 'Кол-во деталей в сборке',
			'min_num'	=> 'Мин. кол-во деталей для заказа',
			'comment'	=> 'Комментарий к детали',
			'type'		=> 'Вид детали',
	);

	public $price_field_types = array(
			'name'		=> 'Ориг. имя детали',
			'name_rus'	=> 'Рус. имя детали',
			'code'		=> 'Парт. номер детали',
			'price_eur'	=> 'Цена детали в eur',
			'price_hrn'	=> 'Цена детали в грн',
	);

	public $sheet_types = array(
			'cabinet' => 'Корпусные элементы',
			'solder' => 'Паечные элементы',
			'prices' => 'Изменения цен',
	);

	/**
	 * @param  $sheet
	 * @return array|bool - array containing all possible fields of this sheet type
	 */
	function get_sheet_fields($sheet)
	{
		$this->load->model('regions_model');
		$regions = $this->regions_model->getFieldValuesArray();

		if ($sheet === 'cabinet' || $sheet === 'solder') {
			return array_merge($this->part_field_types, $regions);
		} elseif ($sheet === 'prices') {
			return array_merge($this->price_field_types, $regions);
		} else {
			return false;
		}
	}

	/**
	 * returns html of select element, containing all possible cols values
	 *
	 * @param int $sheet_id - id of PHPExcel sheet object
	 * @return string - formed html code of select
	 */
	function possible_values($sheet_id) {
		$html = '<select data-sheet-id="' . $sheet_id . '" name="sheet' . $sheet_id . '_cols_values[]" class="col-values-select">'
				. '<option value="0"></option>';

		$used_fields = array();

		$html .= '<optgroup label="деталь">';
		foreach ($this->part_field_types as $val => $desc) {
			if (!in_array($val, $used_fields)) {
				$used_fields[] = $val;
				$html .= '<option value="' . $val . '">' . $desc .'</option>';
			}
		}
		$html .= '</optgroup>';

		$html .= '<optgroup label="прайс-лист">';
		foreach ($this->price_field_types as $val => $desc) {
			if (!in_array($val, $used_fields)) {
				$used_fields[] = $val;
				$html .= '<option value="' . $val . '">' . $desc .'</option>';
			}
		}
		$html .= '</optgroup>';

		$this->load->model('regions_model');
		$html .= '<optgroup label="регионы">';
		foreach ($this->regions_model->getFieldValuesArray() as $val => $desc) {
			if (!in_array($val, $used_fields)) {
				$used_fields[] = $val;
				$html .= '<option value="' . $val . '">' . $desc .'</option>';
			}
		}
		$html .= '</optgroup>'
				. '</select>';
		return $html;
	}

	/**
	 * returns formed html of select, with all possible sheet types
	 *
	 * @param  $id
	 * @return string - formed html of select element
	 */
	function possible_sheet_types($id) {
		$html = '<select data-sheet-id="' . $id . '" name="sheet_type' . $id . '" class="sheet-type-select">'
				. '<option value="0" selected="selected"></option>';
		foreach ($this->sheet_types as $val => $desc) {
			$html .= '<option value="' . $val . '">' . $desc .'</option>';
		}
		$html .= '</select>';
		return $html;
	}

	/**
	 * @param string $inputFileName - full path to excel file
	 * @return PHPExcel object | void
	 */
	function init_phpexcel_object($inputFileName)
	{
		$this->load->library('PHPExcel');
		// Identify the type of $inputFileName
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		// Create a new Reader of the type that has been identified
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		// Load $inputFileName to a PHPExcel Object
		try {
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file: '.$e->getMessage());
		}
		return $objPHPExcel;
	}

	function get_sheet_data($objWorksheet, $sheet_info)
	{
		$data = $this->get_raw_sheet_data($objWorksheet, $sheet_info);
		if ($sheet_info['type'] == 'cabinet') {
			$data = $this->_process_cabinet_parts_sheet_data($data, $sheet_info);
		} elseif ($sheet_info['type'] == 'solder') {
			$data = $this->_process_solder_parts_sheet_data($data, $sheet_info);
		} elseif ($sheet_info['type'] == 'prices') {
			$data = $this->_process_prices_sheet_data($data, $sheet_info);
		}

		return $data;
	}

	/**
	 * returns some number of first sheet rows, as demo data to display on import page
	 * @param object $objWorksheet
	 * @param int $tRows
	 * @param int $tCols
	 * @return array
	 */
	function getDemoRows($objWorksheet, $tRows, $tCols) {
		// Fetch the array with data of the rows
		$data = array();
		$row = 0;
		while (count($data) < 4) {
			if ($row == 0) {// form first row with cols indexes
				for ($col = 0; $col < $tCols; ++$col) {
					$data[$row][$col] = PHPExcel_Cell::stringFromColumnIndex($col);
				}
				$row += 1;
				continue;
			}
			// Fetch the data of the columns
			$data[$row] = array();
			for ($col = 0; $col < $tCols; ++$col) {
				$data[$row][$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
			// get rid of empty rows
			$data = array_filter($data, array($this, 'filter_null_rows'));
			$row += 1;
			if ($row > $tRows) break;
		}
		$data = array_merge(array(), $data);// reset array keys numbers
		return $data;
	}

	/**
	 * returns cell value, obtained throught PHPExcel api
	 * @param int $a cell number
	 * @return str value of cell with provided number in current row
	 */
	function map_cell_index_to_data($a)
	{
		return $this->_worksheet->getCellByColumnAndRow($a, $this->_row)->getValue();
	}

	/**
	 * if strlen of imploded supplied array equals '' -> this array is empty
	 * @param array $a input element of testing array
	 * @return bool - if array is fully empty
	 */
	function filter_null_rows($a)
	{
		return strlen(implode($a)) <= 0 ? false : true;
	}

	/**
	 * @param object $objWorksheet - PHPExcel sheet object
	 * @param array $sheet_info - properties of sheet
	 * @return array - supplied rows in keyed array excluding empty rows
	 */
	function get_raw_sheet_data($objWorksheet, $sheet_info){
		$this->_worksheet = $objWorksheet;

		$highestRow = $objWorksheet->getHighestRow();

		// Fetch the array with data of the rows
		$data = array();
		for ($row = 0; $row <= $highestRow; ++$row) {
			$this->_row = $row;
			// Fetch the data of the columns
			$data[$row] = array_map(
								array(&$this, 'map_cell_index_to_data'),
								array_keys($sheet_info['col_vals'])
							);
			array_set_row_keys_structure($data[$row], $sheet_info['col_vals_keys_resseted']);
		}
		// get rid of empty rows
		$data = array_filter($data, array($this, 'filter_null_rows'));
		$data = array_merge(array(), $data);// reset array keys numbers
		return $data;
	}

	/**
	 * performs trim on array with sheet data (deletes rows with unexpected part.number values)
	 * @param array $input - sheet data
	 * @param array $sheet_info - properties of sheet (vendor, model etc)
	 * @return array
	 */
	function _process_prices_sheet_data($input, $sheet_info)
	{
		// check reqired fields
		if (!isset($input[0]['code']) || !isset($input[0]['name'])) {
			return false;
		}

		foreach ($input as $rowN => $row) {
			if (strlen($row['code']) < 4 || preg_match('/^x+/i', strtolower($row['code'])) || strtolower($row['code']) == 'code') {
				unset($input[$rowN]);
			}

			if (isset($row['name']) && strtolower($row['name']) == 'description') {
				unset($input[$rowN]);
			}

			if (isset($row['price_hrn']) && floatval($row['price_hrn']) < 0) {
				unset($input[$rowN]);
			}
			if (isset($row['price_eur']) && floatval($row['price_eur']) < 0) {
				unset($input[$rowN]);
			}
		}

		// reset array keys
		return array_merge(array(), $input);
	}

	/**
	 * performs trim on array with sheet data (deletes rows with unexpected part.number values)
	 * @param array $input - sheet data
	 * @param array $sheet_info - properties of sheet (vendor, model etc)
	 * @return array
	 */
	function _process_cabinet_parts_sheet_data($input, $sheet_info)
	{
		// process data
		foreach ($input as $rowN => $row) {
			if (strlen($row['code']) < 4 || preg_match('/^x+/i', strtolower($row['code'])) || strtolower($row['code']) == 'code') {
				unset($input[$rowN]);
			}
		}
		// reset array keys
		return array_merge(array(), $input);
	}

	/**
	 * performs trim on array with sheet data (deletes rows with unexpected part.number values)
	 * @param array $input - sheet data
	 * @param array $sheet_info - properties of sheet (vendor, model etc)
	 * @return array
	 */
	function _process_solder_parts_sheet_data($input, $sheet_info) {
		// process data
		foreach ($input as $rowN => $row) {
			if (strlen($row['code']) < 4 || preg_match('/^x+/i', strtolower($row['code'])) || strtolower($row['code']) == 'code') {
				unset($input[$rowN]);
			}
		}
		// reset array keys
		return array_merge(array(), $input);
	}

	public function save_import_to_temp_table($arr)
	{
		$this->db->insert('temp_imports', array('import_data' => serialize($arr)));
		return $this->db->insert_id();
	}

	public function get_import_from_temp_table($id)
	{
		$data = $this->db->where('import_id', $id)->get('temp_imports', 1);
		if ($data->num_rows() <= 0) return false;
		$data = $data->row_array();
		return unserialize($data['import_data']);
	}

	public function get_last_imports_data()
	{
		$data = $this->db->order_by('import_id', 'desc')->get('temp_imports', 3);
		if ($data->num_rows() <= 0) return false;
		$data = $data->result_array();
		foreach ($data as $k => $one) {
			$data[$k]['import_data'] = unserialize($one['import_data']);
		}
		return $data;
	}

	public function save_sheet_to_temp_table($import, $sheet_data)
	{
		$this->db->insert('temp_sheets', array( 'import_id' => $import, 'sheet_data' => serialize($sheet_data) ));
		return $this->db->insert_id();
	}

	function get_sheets_from_temp_table($id)
	{
		$data = $this->db->where('import_id', $id)->order_by('sheet_id')->get('temp_sheets');
		if ($data->num_rows() <= 0) return false;
		return array_map('get_temp_unserialized_sheets_data', $data->result_array());
	}

	/**
	 * @param $sheet
	 * @param $data - array of data rows
	 * @return void
	 */
	public function save_sheet_data_to_temp_table($sheet, $data)
	{
		$insert = array_map('get_temp_serialized_values', array_keys($data), $data, array_fill(0, count($data), $sheet));
		if ($this->db->insert_batch('temp_sheets_data', $insert)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_sheet_data_from_temp_table_count($sheet, $page){
		$data = $this->db->query('SELECT COUNT(id) as num FROM temp_sheets_data WHERE sheet_id = ' . $sheet)->row_array();
		return array(
			'total' => $data['num'],
			'pages' => ceil($data['num'] / $this->per_page),
			'page' => $page + 1,
			'per_page' => $this->per_page,
		);
	}

	public function get_sheet_data_from_temp_table($sheet, $page)
	{
		$data = $this->db->query('SELECT * FROM temp_sheets_data WHERE sheet_id = ' . $sheet . ' ORDER BY id ASC LIMIT ' . $page * $this->per_page . ', ' . $this->per_page);
		if ($data->num_rows() > 0) {
			return array_map('get_temp_unserialized_values', $data->result_array());
		} else {
			return false;
		}
	}

	public function remove_sheet_data_from_temp_table($rows){
		$this->db->query('DELETE from temp_sheets_data WHERE id IN (' . implode(',', $rows) . ')');
	}

}