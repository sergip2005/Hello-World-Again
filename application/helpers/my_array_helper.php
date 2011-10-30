<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function array_change_key_name( $orig, $new, &$array )
{
	$array[$new] = $array[$orig];
	unset( $array[$orig] );
	return $array;
}

/**
 * changes array keys of $data array to values of $pattern array
 * called on row from xls sheat
 *
 * @param array $data - row of parsed xml data
 * @param array $pattern - array with values of names to set
 * @return void
 */
function array_set_row_keys_structure(&$data, $pattern)
{
	foreach ($pattern as $nn => $vv) {
		array_change_key_name($nn, $vv, $data);
	}
}

function array_values_to_option_strings($a)
{
	return '<option value="' . $a['id'] . '">' . $a['name'] .'</option>';
}