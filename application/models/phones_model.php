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
				WHERE v.name = "' . $vendor . '" AND p.model = "' . $model . '"
				ORDER BY v.name';
		return $this->db->query($query)->result_array();
	}

	public function getAllVendorModels($vendor, $type = 'select')
	{
		if ($vendor == 'first') {
			$vendor = $this->db->query('SELECT id FROM vendors ORDER BY name LIMIT 1')->row_array();
			$vendor = $vendor['id'];
		}
		$query = 'SELECT id, model as name
				  FROM `phones`
				  WHERE vendor_id = ' . $vendor . '
				  ORDER BY model';
		$res = $this->db->query($query)->result_array();
		if ($type == 'plain') {
			return $res;
		} elseif ($type == 'select') {
			return array_map('array_values_to_option_strings', $res);
		}
	}
}
