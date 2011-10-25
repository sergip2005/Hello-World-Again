<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones_model extends CI_Model
{
    public function getAllParts()
	{
		$mod = array();
		$vendor = '';
		$query = 'SELECT phones.model AS model, vendors.name AS vendor
				  FROM `phones`
				  LEFT JOIN `vendors` ON phones.vendor_id = vendors.id
				  WHERE vendors.name IS NOT NULL
				  ORDER BY vendors.name';
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
		$query = 'SELECT code, parts.name as parts, phones.model, vendors.name, parts.type
				  FROM `phones_parts`
				  LEFT JOIN `parts` ON phones_parts.part_id = parts.id
				  LEFT JOIN `phones` ON phones_parts.phone_id = phones.id
				  LEFT JOIN `vendors` ON phones.vendor_id = vendors.id
				  WHERE vendors.name = "' . $vendor . '" AND phones.model = "' . $model . '"
				  ORDER BY vendors.name';
		$q = $this->db->query($query)->result_array();
		return $q;
	}
}
