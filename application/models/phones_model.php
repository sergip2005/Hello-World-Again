<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones_model extends CI_Model
{
    public function getAllParts()
	{
		$mod = array();
		$vendor = '';
		$query = 'SELECT p.model AS model, v.name AS vendor
				  FROM `phones` p
				  LEFT JOIN `vendors` v ON p.vendor_id = v.id
				  WHERE v.name IS NOT NULL
				  ORDER BY v.name';
		$q = $this->db->query($query);
		foreach ($q->result_array() as  $key=>$row)
		{
			$vendor = $key == 0 ? $row['vendor'] : $vendor;
			$model = $row['model'];
			if($vendor == $row['vendor']  ) {
				$mod[] = $model;
			}else {
				$group_vendor[$vendor] = $mod;
				unset($mod);
				$mod[] = $model;
				$vendor = $row['vendor'];
			}
			if($key == count($row))	$group_vendor[$vendor] =  $mod;
		}
		return  $group_vendor;
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
