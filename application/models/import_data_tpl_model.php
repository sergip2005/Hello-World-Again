<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_data_tpl_model extends CI_Model
{
	const TABLE = 'import_data_templates';

	function getAll()
	{
		return $this->db->query('SELECT * FROM ' . self::TABLE . ' ORDER BY name')->result_array();
	}

	function getSelect(){
		$tpls = $this->getAll();

		$html = '<select><option value="0" selected="selected"> --- </option>';
		foreach ($tpls as $tpl) {
			$html .= '<option value="' . $tpl['id'] . '" data-values="' . $tpl['values'] . '">' . $tpl['name'] . '</option>';
		}
		return $html . '</select>';
	}

	function save($data)
	{
		$this->db->insert(self::TABLE, $data);
	}

	function remove($id)
	{
		$this->db->delete(self::TABLE, array('id' => $id));
	}

}