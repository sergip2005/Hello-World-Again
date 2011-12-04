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

	/**
	 * get parts by vendor and model names
	 * @param string $vendor
	 * @param string $model
	 * @param string $region
	 * @return array|boolean
	 */
	public function getPartsByName($vendor, $model, $region = '')
	{
		$q1 = 'SELECT
				  pa.min_num as min_num, pp.cct_ref as cct_ref, pa.code as code, pa.name as name,
				  pa.name_rus as name_rus, pa.price as price, pp.num as num, pa.type as type, p.rev_num as rev_num
				  FROM `phones_parts` pp
				  LEFT JOIN `parts` pa ON pp.part_id = pa.id
				  LEFT JOIN `phones` p ON pp.phone_id = p.id
				  LEFT JOIN `vendors` v ON p.vendor_id = v.id
				  WHERE v.name = ? AND p.model = ?
				  ORDER BY v.name';
		$q2 = 'SELECT
				  pa.min_num as min_num, pp.cct_ref as cct_ref, pa.code as code, pa.name as name,
				  pa.name_rus as name_rus, pa.price as price, pp.num as num, pa.type as type, r.name as r_name, p.rev_num as rev_num
				  FROM `phones_parts` pp
				  LEFT JOIN `parts` pa ON pp.part_id = pa.id
				  LEFT JOIN `phones` p ON pp.phone_id = p.id
				  LEFT JOIN `vendors` v ON p.vendor_id = v.id
				  LEFT JOIN `phones_parts_regions_rel` pprr ON pprr.part_id = pa.id
				  LEFT JOIN `regions` r ON r.id = pprr.region_id
				  WHERE v.name = ? AND p.model = ?
				  AND r.id = (SELECT id FROM regions where `default` = 1)
				  ORDER BY v.name';
		$query = $region == 'all' ? $q1 : $q2;
		return $this->db->query($query, array($vendor, $model, $region))->result_array();
	}

	/**
	 * @param int|string $vendor_id
	 * @param int|string $model_id
	 * @param string $region
	 * @param bool|array $options
	 * @return array|bool
	 */
	public function getParts($vendor_id, $model_id, $region = '', $options = false)
	{
		$q1 = 'SELECT
				pp.id, pp.cct_ref as cct_ref, pp.num as num,
				pa.id as part_id, pa.min_num as min_num, pa.code as code, pa.name as name, pa.ptype,
				pa.name_rus as name_rus, pa.price as price, pa.type as type, pa.mktel_has as available,
				v.name as vendor_name, v.id as vendor_id,
				p.model as model_name, p.id as model_id
				FROM `parts` pa
				LEFT JOIN `phones_parts` pp ON pp.part_id = pa.id
				LEFT JOIN `phones` p ON pp.phone_id = p.id
				LEFT JOIN `vendors` v ON pa.vendor_id = v.id
				WHERE pa.vendor_id = ?';

		$q2 = 'SELECT
				pp.id, pp.cct_ref as cct_ref, pp.num as num,
				pa.id as part_id, pa.min_num as min_num, pa.code as code, pa.name as name, pa.ptype,
				pa.name_rus as name_rus, pa.price as price, pa.type as type, pa.mktel_has as available,
				v.name as vendor_name, v.id as vendor_id,
				p.model as model_name, p.id as model_id
				FROM `parts` pa
				LEFT JOIN `phones_parts` pp ON pp.part_id = pa.id
				LEFT JOIN `phones` p ON pp.phone_id = p.id
				LEFT JOIN `phones_parts_regions_rel` pprr ON pprr.part_id = pa.id
				LEFT JOIN `vendors` v ON pa.vendor_id = v.id
				LEFT JOIN `regions` r ON r.id = pprr.region_id
				WHERE pa.vendor_id = ?
				AND r.id = (SELECT id FROM regions where `default` = 1)';
		if ($model_id === 'all') {
			$q1 .= '';
			$q2 .= '';
			$values = array($vendor_id, $region);
		} elseif ($model_id === 'none') {
			$q1 .= ' AND (pp.phone_id = 0 OR pp.phone_id IS NULL)';
			$q2 .= ' AND (pp.phone_id = 0 OR pp.phone_id IS NULL)';
			$values = array($vendor_id, $region);
		} else {
			$q1 .= ' AND pp.phone_id = ?';
			$q2 .= ' AND pp.phone_id = ?';
			$values = array($vendor_id, $model_id, $region);
		}

		if ($options !== false) {}

		$query = $region == 'all' ? $q1 : $q2;
		//die(print_r(array($query, $values), true));
		$p = $this->db->query($query, $values);
		if ($p->num_rows() > 0) {
			$p = $p->result_array();
		} else {
			return false;
		}
		$pp = array_filter($p, 'get_only_phone_parts');
		$r = count($pp) > 0 ? $this->getPhonePartsRegions(array_map('narrow_to_id_field_only', $pp)) : array();
		return array(
			'parts' => $p,
			'regions' => $r
		);
	}

	public function getVendorModels($vendor)
	{
		$query = 'SELECT p.id, p.model as name
				  FROM `phones` p
				  LEFT JOIN `vendors` v ON p.vendor_id = v.id
				  WHERE `name` = ? ORDER BY model';
		$res = $this->db->query($query, array($vendor))->result_array();
			foreach ($res as $model) {
				$ret[] = '<li id="' . $model['id'] . '"><a href="/phones/' . strtolower($vendor) . '/' . str_replace(' ', '_', $model['name']) . '">' . $model['name'] . '</a></li>';
			}
			return $ret;
	}

	public function getAllVendorModels($vendor, $type = 'select', $format_options = array())
	{
		if ($vendor == 'first') {
			$vendor = $this->db->query('SELECT id FROM vendors ORDER BY name ASC LIMIT 1')->row_array();
			$vendor = $vendor['id'];
		}

		$query = 'SELECT id, model as name, rev_num, rev_desc, rev_date FROM `phones` WHERE vendor_id = ? ORDER BY model';
		$res = $this->db->query($query, array($vendor));
		if ($res->num_rows > 0) {
			$res = $res->result_array();
		} else {
			return false;
		}

		if ($type == 'select' && !isset($format_options['selected'])) {
			return array_map('array_values_to_option_strings', $res);
		} else if ($type == 'select' && !empty($format_options['selected'])) {
			$ret = array();
			foreach ($res as $model) {
				$selected = $model['id'] == $format_options['selected'] ? ' selected="selected"' : '';
				$ret[] = '<option value="' . $model['id'] . '"' . $selected . ' data-rev-num="' . $model['rev_num'] . '" data-rev-desc="' . $model['rev_desc'] . '" data-rev-date="' . $model['rev_date'] . '">' . $model['name'] . '</option>';
			}
			return $ret;
		} else {
			return $res;
		}
	}

	/**
	 * @param int $mId
	 * @return array - db row info from 'phones' table
	 */
	public function getModelInfo($mId)
	{
		return $this->db->query('SELECT * FROM `phones` WHERE `id` = ? LIMIT 1', array('id' => $mId))->row_array();
	}

	/**
	 * if model with such name already exists under this vendor -> returns its id
	 * otherwise -> creates it, and returns new model id
	 * @param string $name
	 * @param int $vendor
	 * @return bool|int - id of newly created or existing model in 'phones' table
	 */
	public function getOrCreateModel($name, $vendor){
		if (empty($name)) { return false; }

		$sql = 'SELECT id FROM `phones` WHERE `model` = ? AND `vendor_id` = ? LIMIT 1';
		$id = 0;
		$res = $this->db->query($sql, array($name, $vendor));
		if ($res->num_rows() > 0) {
			$res = $res->row();
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

	public function getModel($id)
	{
		$sql = 'SELECT * FROM `phones` WHERE id = ' . $id . ' LIMIT 1';
		$res = $this->db->query($sql);
		if ($res->num_rows() > 0) {
			return $res->row_array();
		} else {
			return false;
		}
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return int - id of newly created of existing `phones_parts` record
	 */
	public function savePart($id, $data)
	{
		if ($id <= 0) {
			// check if this part is allready in this phone model by (part_id and position)
			$part = $this->db->query(
					'SELECT `id` FROM `phones_parts` WHERE `phone_id` = ? AND `part_id` = ? AND `cct_ref` = ? LIMIT 1',
					array($data['phone_id'], $data['part_id'], $data['cct_ref'])
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

	public function getPhonePartsRegions($ids)
	{
		$q = 'SELECT pprr.part_id, pprr.region_id FROM phones_parts_regions_rel pprr WHERE ';
		//$this->db->select('pprr.part_id, pprr.region_id')->from('phones_parts_regions_rel pprr');

		if (is_array($ids)) {
			$q .= 'pprr.part_id IN (' . implode(', ', $ids) . ') ';
			//$this->db->where_in('pprr.part_id', $ids);
		} elseif ((is_string($ids) || is_int($ids)) && strlen($ids) > 0) {
			$q .= 'pprr.part_id = ' . $ids;
			//$this->db->where('pprr.part_id', $ids);
		} else {
			return false;
		}

		$res = $this->db->query($q);
		if ($res->num_rows() <= 0) return false;

		$ret = array();
		foreach ($res->result_array() as $row) {
			$ret[$row['part_id']][] = $row['region_id'];
		}

		return $ret;
	}

	/**
	 * returns info of parts with provided codes
	 * @param array|string $codes - array of codes to select, or one code
	 * @param int $vendor_id
	 * @return array|bool
	 */
	function getPartsDataByCode($codes, $vendor_id)
	{
		$q = 'SELECT
				pt.id, pt.code, pt.name, pt.type, pt.ptype,
				pt.name_rus, pt.url, pt.mktel_has, pt.price, pt.min_num
			FROM parts AS pt
			LEFT JOIN phones_parts AS pp ON pt.id = pp.part_id
			LEFT JOIN phones AS phn ON phn.id = pp.phone_id
			WHERE phn.vendor_id = ' . (int)$vendor_id;

		if (is_array($codes)) {
			$q .= ' AND pt.code in ("' . implode($codes, '","') . '")';
		} elseif ((is_string($codes) || is_int($codes)) && strlen($codes) > 0) {
			$q .= ' AND pt.code = "' . $codes . '"';
		} else {
			return false;
		}

		$res = $this->db->query($q);
		if ($res->num_rows() <= 0) return false;
		$res = $res->result_array();

		return array(
				'data' => process_to_code_keyed_array($res),
				'ids' => array_map('narrow_to_id_field_only', $res)
			);
	}

	/**
	 * returns existing data of provided model
	 * @param array|str $codes
	 * @param int $vendor_id
	 * @param int $model_id
	 * @return array
	 */
	public function getPrevDataState($codes, $vendor_id, $model_id){
		$p = $this->getPartsDataByCode($codes, $vendor_id);// array of parts with passed codes: 'code' => properties array
		$pp = $this->getPhonePartsByPartId($p['ids'], $model_id);
		$r = $this->getPhonePartsRegions($pp['ids']);

		return array(
			'parts' => $p['data'],
			'phone_parts' => $pp['data'],
			'regions' => $r
		);
	}

	/**
	 * returns data from phones_parts table by model_id and part_id array provided
	 * @param  $ids
	 * @param  $model_id
	 * @return array|bool
	 */
	public function getPhonePartsByPartId($ids, $model_id)
	{
		$this->db
				->select('pp.id, pp.part_id, pp.phone_id, pp.cct_ref, pp.num, pp.comment, p.code')
				->from('phones_parts AS pp')
				->join('parts AS p', 'p.id = pp.part_id', 'left')
				->where('pp.phone_id', $model_id);

		if (is_array($ids)) {
			$this->db->where_in('pp.part_id', $ids);
		} elseif ((is_string($ids) || is_int($ids)) && strlen($ids) > 0) {
			$this->db->where('pp.part_id', $ids);
		} else {
			return false;
		}

		$res = $this->db->get();
		if ($res->num_rows() <= 0) return false;

		$res = $res->result_array();

		return array(
			'data' => process_to_code_keyed_groupped_array($res),
			'ids' => array_map('narrow_to_id_field_only', $res)
		);
	}

	/**
	 * @param int $mId - id of phone
	 * @param array $pIds - array of ids of phone parts
	 * @return void
	 */
	public function removePhoneParts($mId, $pIds){
		$this->db->where('phone_id', $mId)->where_in('id', $pIds)->delete('phones_parts');
		return true;
	}
}
