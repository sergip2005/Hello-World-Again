<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_model extends CI_Model
{
	private $_worksheet;
	private $_row;

	public $rev_field_types = array(
			'rev_num'	=> 'Номер ревизии листа',
			'rev_date'	=> 'Дата ревизии листа',
			'rev_desc'	=> 'Описание ревизии листа',
		);

	public $part_field_types = array(
			'name'		=> 'Ориг. имя детали',
			'code'		=> 'Парт. номер детали',
			'cct_ref'	=> 'Позиция детали на рисунке',
			'type'		=> 'Тип детали',
			'num'		=> 'Кол-во деталей в сборке',
			'comment'	=> 'Комментарий к детали',
	);

	public $price_field_types = array(
			'name'		=> 'Ориг. имя детали',
			'code'		=> 'Парт. номер детали',
			'price_eur'	=> 'Цена детали в eur',
			'price_$'	=> 'Цена детали в $',
			'price_grn'	=> 'Цена детали в грн',
	);

	function get_sheet_fields($sheet)
	{
		if ($sheet === 'rev') {
			return $this->rev_field_types;
		} elseif ($sheet === 'cabinet' || $sheet === 'solder') {
			return $this->part_field_types;
		} elseif ($sheet === 'prices') {
			return $this->price_field_types;
		} else {
			return false;
		}
	}

	/**
	 * @param str $inputFileName - full path to excel file
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

		if ($sheet_info['type'] == 'rev') {
			$data = $this->_process_rev_sheet_data($data, $sheet_info);
		} elseif ($sheet_info['type'] == 'cabinet') {
			$data = $this->_process_cabinet_parts_sheet_data($data, $sheet_info);
		} elseif ($sheet_info['type'] == 'solder') {
			$data = $this->_process_solder_parts_sheet_data($data, $sheet_info);
		} elseif ($sheet_info['type'] == 'prices') {
			$data = $this->_process_prices_sheet_data($data, $sheet_info);
		}

		return $data;
	}

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
	 * @param int $a cell number
	 * @return str value of cell with provided number in current row
	 */
	function map_cell_index_to_data($a)
	{
		return $this->_worksheet->getCellByColumnAndRow($a, $this->_row)->getValue();
	}

	/**
	 * @param array $a input element of testing array
	 * @return bool - if array is fully empty
	 */
	function filter_null_rows($a)
	{
		return strlen(implode($a)) <= 0 ? false : true;
	}

	/**
	 * @return array with data in rows excluding empty rows
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
	 * receives fields rev_num, rev_date, rev_desc
	 *
	 * @param array $input - array with not null rows of this sheet
	 * @param array $sheet_info - array with sheet details
	 * @return void
	 */
	function _process_rev_sheet_data($input, $sheet_info)
	{
		// this sheet may contain comments splitted into multiply rows in 'rev_desc' field
		// so if there is data only in rev_desc field then append this data to prev field

		foreach($input as $row){
			
		}
		return $input;
	}

	function _process_prices_sheet_data($input, $sheet_info)
	{
		// need to have 
		foreach($input as $one){
			
		}
		return $input;
	}

	function _process_cabinet_parts_sheet_data($input, $sheet_info)
	{
		// check reqired fields
		if (!isset($input[0]['code']) || !isset($input[0]['name'])) {
			return false;
		}

		// process data
		foreach ($input as $rowN => $row) {
			if (strlen($row['code']) < 4 || preg_match('/^x+/i', strtolower($row['code'])) || strtolower($row['code']) == 'code') {
				unset($input[$rowN]);
			}
		}

		return array_merge(array(), $input);
	}

	function _process_solder_parts_sheet_data($input, $sheet_info) {
		// process data
		foreach ($input as $rowN => $row) {
			if (strlen($row['code']) < 4 || preg_match('/^x+/i', strtolower($row['code'])) || strtolower($row['code']) == 'code') {
				unset($input[$rowN]);
			}
		}

		return array_merge(array(), $input);
	}

}