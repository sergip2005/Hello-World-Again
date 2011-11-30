<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * returns new 1-dimensional array with 'code' field values only
 * @param array $a - array of part properties
 * @return string - code value
 */
function narrow_to_code_field_only($a)
{
	return $a['code'];
}

/**
 * returns new 1-dimensional array with 'id' field values only
 * @param array $a
 * @return array
 */
function narrow_to_id_field_only($a)
{
	return $a['id'];
}

/**
 * receives array of parts data
 * returns the same array keyed and grouped by 'code' field
 * @param array $a - parts data
 * @return array - rekeyed array
 */
function process_to_code_keyed_groupped_array($a)
{
	$b = array();
	foreach ($a as $row) {
		if (isset($row['id']) && $row['id'] > 0) {// preserve id as key if id exists
			$b[$row['code']][$row['id']] = $row;
		} else {
			$b[$row['code']][] = $row;
		}
	}
	return $b;
}

/**
 * receives array of parts data
 * returns the same array keyed by 'code' field
 * @param array $a - parts data
 * @return array - rekeyed array
 */
function process_to_code_keyed_array($a)
{
	$b = array();
	foreach ($a as $row) {
		$b[$row['code']] = $row;
	}
	return $b;
}

/**
 * receives array of parts data
 * returns the same array keyed by 'id' field
 * @param array $a - parts data
 * @return array - rekeyed array
 */
function process_to_id_keyed_array($a)
{
	$b = array();
	foreach ($a as $row) {
		$b[$row['id']] = $row;
	}
	return $b;
}

/**
 * used in array_map
 * @param $key
 * @param $val
 * @param $sheet
 * @return array
 */
function get_temp_serialized_values($key, $val, $sheet){
	return array('row_id' => $key, 'row_data' => serialize($val), 'sheet_id' => $sheet );
}

function get_temp_unserialized_sheets_data($sheet){
	return array_merge(array('sheet_id' => $sheet['sheet_id']), unserialize($sheet['sheet_data']));
}

function get_temp_unserialized_values($row){
	return array_merge(array('row_id' => $row['id']), unserialize($row['row_data']));
}