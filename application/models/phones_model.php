<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones_model extends CI_Model
{
	public function getAllParts()
	{
		$query = 'SELECT phones.model AS model, vendors.name AS vendor, vendors.id AS vendor_id
				  FROM `phones`
				  LEFT JOIN `vendors` ON phones.vendor_id = vendors.id
				  WHERE vendors.name IS NOT NULL
				  ORDER BY vendors.name';
		$q = $this->db->query($query);
		$data = array();
		foreach ($q->result_array() as $row)
		{
			$data[$row['vendor']][] = $row['model'];
		}
		return  $data;
	}

	public function getParts($vendor, $model)
	{
		$query = 'SELECT
					pa.min_num as min_num, pp.cct_ref as cct_ref, pa.code as code, pa.name as name,
					pa.name_rus as name_rus, pa.price as price, pp.num as num, pa.type as type
				FROM `phones_parts` pp
				LEFT JOIN `parts` pa ON pp.part_id = pa.id
				LEFT JOIN `phones` p ON pp.phone_id = p.id
				LEFT JOIN `vendors` v ON p.vendor_id = v.id
				WHERE v.name = ? AND p.model = ?
				ORDER BY v.name';
		return $this->db->query($query, array($vendor, $model))->result_array();
	}

	public function getAllVendorModels($vendor, $type = 'select', $format_options = array())
	{
		if ($vendor == 'first') {
			$vendor = $this->db->query('SELECT id FROM vendors ORDER BY name LIMIT 1')->row_array();
			$vendor = $vendor['id'];
		}
		$query = 'SELECT id, model as name FROM `phones` WHERE vendor_id = ? ORDER BY model';
		$res = $this->db->query($query, array($vendor))->result_array();
		if ($type == 'select' && !isset($format_options['selected'])) {
			return array_map('array_values_to_option_strings', $res);
		} else if ($type == 'select' && !empty($format_options['selected'])) {
			$ret = array();
			foreach ($res as $model) {
				$selected = $model['id'] == $format_options['selected'] ? ' selected="selected"' : '';
				$ret[] = '<option value="' . $model['id'] . '"' . $selected . '>' . $model['name'] . '</option>';
			}
			return $ret;
		} else {
			return $res;
		}
	}

	public function getOrCreateModel($name, $vendor){
		if (empty($name)) { return false; }

		$sql = 'SELECT id FROM `phones` WHERE `model` = ? AND `vendor` = ? LIMIT 1';
		$id = 0;
		if ($res = $this->db->query($sql, array($this->db->escape($name), $this->db->escape($vendor)))->row()) {
			$id = $res->id;
		}

		if ($id == 0) {
			$this->db->insert('phones', array(
					'model' => $name,
					'show' => 1,
					'vendor_id' => $vendor
				));
			$id = $this->db->insert_id();
		}

		return $id > 0 ? $id : false;
	}

	public function save($id, $data)
	{
		if ($id <= 0) {
			// check if this part is allready in this phone model
			$part = $this->db->query(
					'SELECT `id` FROM phones_parts WHERE phone_id = ? AND part_id = ? LIMIT 1',
					array($data['phone_id'], $data['part_id'])
				);
			$id = $part->num_rows() > 0 ? $part->row()->id : 0;
		}

		if ($id > 0) { // update
			$this->db->where('id', $id)->update('phones_parts', $data);
		} else { // insert
			$this->db->insert('phones_parts', $data);
			$id = $this->db->insert_id();
		}
		return $id;
	}
}
