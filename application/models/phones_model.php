<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phones_model extends CI_Model
{
	public $phonePartFields = array(
		'cct_ref', 'num', 'comment'
	);

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

	public function getParts($vendor, $model, $region = '')
	{
		$query = 'SELECT
				  pa.min_num as min_num, pp.cct_ref as cct_ref, pa.code as code, pa.name as name,
				  pa.name_rus as name_rus, pa.price as price, pp.num as num, pa.type as type, r.name as r_name
				  FROM `phones_parts` pp
				  LEFT JOIN `parts` pa ON pp.part_id = pa.id
				  LEFT JOIN `phones` p ON pp.phone_id = p.id
				  LEFT JOIN `vendors` v ON p.vendor_id = v.id
				  LEFT JOIN `phones_parts_regions_rel` pprr ON pprr.part_id = pa.id
				  LEFT JOIN `regions` r ON r.id = pprr.region_id
				  WHERE v.name = ? AND p.model = ?
				  AND IF (? = "all", 1, r.id = (SELECT id FROM regions where `default` = 1))
				  ORDER BY v.name';
		return $this->db->query($query, array($vendor, $model, $region))->result_array();
	}

	public function getVendorModels($vendor)
	{
		$query = 'SELECT p.id, p.model as name
				  FROM `phones` p
				  LEFT JOIN `vendors` v ON p.vendor_id = v.id
				  WHERE `name` = ? ORDER BY model';
		$res = $this->db->query($query, array($vendor))->result_array();
			foreach ($res as $model) {
				$ret[] = '<li id="' . $model['id'] . '"><a href="/phones/' . $vendor . '/' . str_replace(' ', '_', $model['name']) . '">' . $model['name'] . '</a></li>';
			}
			return $ret;
	}

	public function getAllVendorModels($vendor, $type = 'select', $format_options = array())
	{
		if ($vendor == 'first') {
			$vendor = $this->db->query('SELECT id FROM vendors ORDER BY name ASC LIMIT 1')->row_array();
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

	public function getModelInfo($mId)
	{
		return $this->db->query('SELECT * FROM `phones` WHERE `id` = ? LIMIT 1', array('id' => $mId))->row_array();
	}

	public function getOrCreateModel($name, $vendor){
		if (empty($name)) { return false; }

		$sql = 'SELECT id FROM `phones` WHERE `model` = ? AND `vendor_id` = ? LIMIT 1';
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

	/**
	 * @param int $id
	 * @param array $data
	 * @return
	 */
	public function savePart($id, $data)
	{
		/*
		if ($id <= 0) {
			// check if this part is allready in this phone model
			$part = $this->db->query(
					'SELECT `id` FROM phones_parts WHERE phone_id = ? AND part_id = ? LIMIT 1',
					array($data['phone_id'], $data['part_id'])
				);
			$id = $part->num_rows() > 0 ? $part->row()->id : 0;
		}*/

		if ($id > 0) { // update
			$this->db->where('id', $id)->update('phones_parts', $data);
		} else { // insert
			$this->db->insert('phones_parts', $data);
			$id = $this->db->insert_id();
		}
		return $id;
	}

	/**
	 * saves\updates phone model data
	 * @param int $id
	 * @param array $data
	 * @return int - phone model id
	 */
	public function saveModel($id, $data)
	{
		if ($id > 0) { // update
			$this->db->where('id', $id)->update('phones', $data);
		} else { // insert
			$this->db->insert('phones', $data);
			$id = $this->db->insert_id();
		}
		return $id;
	}

	/**
	 * receives data of part already saved in parts table, and saves this part to phone_parts table
	 * @param int $pId
	 * @param array $rowData
	 * @param array $sheetData
	 * @return int - phone part id
	 */
	public function updateOrCreatePhonePart($pId, $rowData, $sheetData){
		// save phone model data
		foreach ($rowData as $n => $v) {
			if (in_array($n, $this->phonePartFields)) {
				$phonePartData[$n] = $v;
			}
		}
		$phonePartData['phone_id'] = $sheetData['phone_id'];
		$phonePartData['part_id'] = $pId;
		// insert or update phone
		return $this->savePart(0, $phonePartData);
	}

	/**
	 * removes all stored part regions
	 * @param int $pId
	 * @return boolean
	 */
	public function resetPartRegions($pId){
		return $this->db->query('DELETE FROM `phones_parts_regions_rel` WHERE `part_id` = ' . intval($pId));
	}

	/**
	 * sets given region_id to given part_id
	 * @param int $pId
	 * @param int $rId
	 * @return boolean
	 */
	public function setRegionToPart($pId, $rId)
	{
		return $this->db->insert('phones_parts_regions_rel', array( 'part_id' => $pId, 'region_id' => $rId ));
	}

	public function updatePartsRegions($pId, $rowData, $sheetData)
	{
		$this->load->model('regions_model');
		$this->resetPartRegions($pId);

		$regions = isset($rowData['regions']) && count($rowData['regions']) > 0 ? $rowData['regions'] : false;
		if ($regions === false) {
			return false;
		}

		foreach ($regions as $rId => $excVal) {
			if ($rId <= 0 || empty($excVal)) continue;

			if (strtolower($excVal) == 'x' || strtolower($excVal) == '+') {
				$this->setRegionToPart($pId, $rId);
			}
		}
	}

	function getPartsDataByCode($codes)
	{
		if (!is_array($codes)) {
			$this->db->where_in('code', $codes);
		} elseif ((is_string($codes) || is_int($codes)) && strlen($codes) > 0)  {
			$this->db->where('code', $codes);
		} else {
			return false;
		}
	}
}
