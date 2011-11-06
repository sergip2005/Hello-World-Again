<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * changes passed key name to given name in source array
 * @param string $orig - original key name
 * @param string $new - new key name
 * @param array $array - source array
 * @return array - source array
 */
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

/**
 * @param array $a - array like ('id' => x, 'name' => x)
 * @return string - option html element
 */
function array_values_to_option_strings($a)
{
	return '<option value="' . $a['id'] . '">' . $a['name'] .'</option>';
}

/**
 * revursive implode - implodes multidimentional arrays
 * @param string $glue
 * @param array $pieces
 * @return string
 */
function r_implode($glue, $pieces)
{
	foreach ($pieces as $r_pieces) {
		if (is_array($r_pieces)) {
			$retVal[] = r_implode($glue, $r_pieces);
		} else {
			$retVal[] = $r_pieces;
		}
	}
	return implode($glue, $retVal);
}