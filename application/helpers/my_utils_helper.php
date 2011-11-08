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
		$b[$row['code']][] = $row;
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

