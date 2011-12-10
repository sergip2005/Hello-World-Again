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
 * array_filter callback
 * checks if 'id' (phone id) property of array is not zero
 * @param array $a
 * @return bool
 */
function get_only_phone_parts($a)
{
	return (intval($a['id']) > 0);
}

/**
 * returns intvaled and decreased parameter of 0
 *
 * @param string|int $val
 * @return int
 */
function get_posted_page($val)
{
	if ($val === 'all') {
		return 'all';
	} else {
		$val = intval($val);
		return $val > 0 ? $val - 1 : 0;
	}
}

/**
 * calculates total pages num, num of elems on current page
 * @param array $p
 */
function calculatePaginationParams(&$p)
{
	$CI =& get_instance();
	$p['per_page'] = $CI->config->item('per_page');
	$p['pages'] = ceil($p['items'] / $p['per_page']);
	$b = $p['page'] * $p['per_page'];
	$e = $b + $p['per_page'];
	$p['current'] = array(
						'begin' => $b + 1,
						'end' => $e > $p['items'] ? $p['items'] : $e
					);
}

function sanitate_input_string($s)
{
	return preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/ui', '', $s);
}