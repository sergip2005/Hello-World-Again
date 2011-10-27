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
		$query = 'SELECT pa.code as code, pa.name as name, pa.name_rus as name_rus, pa.price as price, pp.num as num, pa.type as type
				  FROM `phones_parts` pp
				  LEFT JOIN `parts` pa ON pp.part_id = pa.id
				  LEFT JOIN `phones` p ON pp.phone_id = p.id
				  LEFT JOIN `vendors` v ON p.vendor_id = v.id
				  WHERE v.name = "' . $vendor . '" AND p.model = "' . $model . '"
				  ORDER BY v.name';
		$q = $this->db->query($query)->result_array();
		return $q;
	}
}
