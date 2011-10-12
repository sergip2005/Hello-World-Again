<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function convert_filesize($size) {
	if ($size > 0) {
		$unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb');
		return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i];
	} else {
		return 0; 
	}
}

?>