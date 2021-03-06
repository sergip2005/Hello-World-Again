<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parts_model extends CI_Model
{
	public $partType = array(
		'cabinet' => 'c',
		'solder' => 's',
	);

	public $partTypeName = array(
		'c' => 'механический',
		's' => 'электрический'
	);

	public $partFields = array(
		'name', 'vendor_id', 'type',
		'ptype', 'code', 'name_rus',
		'url', 'mktel_has', 'price', 'min_num'
	);

	public function getOrCreate($name, $vendor)
	{
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

	public function updateOrCreate($rowData, $sheetData)
	{
		$this->load->model('currency_model');

		$partData = array();

		if (isset($sheetData['type']) && isset($this->partType[$sheetData['type']])) {
			$partData['type'] = $this->partType[$sheetData['type']];
		}

		// save part data
		foreach ($rowData as $n => $v) {
			if (in_array($n, $this->partFields)) {
				$partData[$n] = $v;
			}
		}

		$partData['vendor_id'] = $sheetData['vendor_id'];
		$partData['show'] = 1;
		// default vals for min_num
		if (!isset($partData['min_num']) || empty($partData['min_num']) || $partData['min_num'] == 0) {
			if (isset($partData['type']) && $partData['type'] == 'c') {
				$partData['min_num'] = 1;
			} elseif (isset($partData['type']) && $partData['type'] == 's') {
				$partData['min_num'] = 5;
			} else {
				$partData['min_num'] = 1;
			}
		}

		// process price value
		foreach ($this->currency_model->priceFields as $priceField) {
			if (isset($rowData[$priceField]) && floatval($rowData[$priceField]) > 0) {
				$partData['price'] =
						$priceField === $this->currency_model->base
								? floatval($rowData[$priceField])
								: $this->currency_model->convert(end(explode('_', $priceField)), end(explode('_', $this->currency_model->base)), floatval($rowData[$priceField]));
			}
		}

		//die(print_r($partData, true));
		// insert or update part
		return $this->save(0, $partData);
	}

	function save($id, $data)
	{
		if ($id <= 0) {
			// check if this pn is allready in this vendor
			$part = $this->db->query(
					'SELECT `id` FROM parts WHERE code = ? AND vendor_id = ? LIMIT 1',
					array($data['code'], $data['vendor_id'])
				);
			$id = $part->num_rows() > 0 ? $part->row()->id : 0;
		}
		//die($id);

		$data['last_updated'] = date('Y-m-d H:i:s');

		if ($id > 0) { // update
			$this->db->where('id', $id)->update('parts', $data);
		} else { // insert
			$this->db->insert('parts', $data);
			$id = $this->db->insert_id();
		}
		return $id;
	}

	/**
	 * @param  $pn
	 * @return mixed
	 */
	function getPartsByCode($pn, $page)
	{
		$pp = $this->config->item('per_page');
		$q = 'SELECT
				pp.id, pp.num as num, pp.cct_ref as cct_ref,
				pa.code as code, pa.old_code as old_code, pa.name as name, pa.min_num as min_num,
				pa.ptype, pa.name_rus as name_rus, pa.price as price, pa.ptype as ptype,
				pa.type as type, pa.mktel_has as available,
				p.id as model_id, p.model as model_name,
				v.name as vendor_name, v.id as vendor_id
			  FROM `phones_parts` pp
			  LEFT JOIN `parts` pa ON pp.part_id = pa.id
			  LEFT JOIN `phones` p ON pp.phone_id = p.id
			  LEFT JOIN `vendors` v ON p.vendor_id = v.id
			  WHERE pa.code LIKE ?
			  LIMIT ' . ($page * $pp) . ', ' . $pp;
		return $this->db->query($q, '%' . $pn . '%')->result_array();
	}

	function getPartsByVendor_Code($pn, $v)
	{
		$q = 'SELECT
				id, min_num, code, name, ptype, name_rus, price, ptype, type
			  FROM `parts`
			  WHERE code = ?
				AND vendor_id = ?';
		return $this->db->query($q, array($pn, $v))->result_array();
	}

	function getPartsByVendor($v)
	{
		$q = 'SELECT
				id, min_num, code, name, ptype, name_rus, price, ptype, type
			  FROM `parts`
			  WHERE
				vendor_id = ?';
		return $this->db->query($q, array($v))->result_array();
	}

	function countGetPartsByCode($number)
	{
		$q = 'SELECT
			  COUNT(*) as num
			  FROM `phones_parts` pp
			  LEFT JOIN `parts` pa ON pp.part_id = pa.id
			  LEFT JOIN `phones` p ON pp.phone_id = p.id
			  LEFT JOIN `vendors` v ON p.vendor_id = v.id
			  WHERE pa.code LIKE ?';
		return $this->db->query($q, '%' . $number . '%')->row()->num;
	}

	/**
	 * @param $query
	 * @param $parameter
	 * @return mixed
	 */
	function searchParts($query, $parameter, $page)
	{
		$pp = $this->config->item('per_page');
		$where = ' WHERE ';
		if($parameter == 'models' ) {
			$where .= ' p.model LIKE ?';
			$params = '%' . $query . '%';
		}

		if($parameter == 'part_name' ) {
			$where .= ' pa.name LIKE ? OR pa.name_rus LIKE ?';
			$params = array('%' . $query . '%', '%' . $query . '%');
		}

		if($parameter == 'part_code' ) {
			$where .= ' pa.code LIKE ?';
			$params = array('%' . $query . '%');
		}

		$q = 'SELECT
				pa.id as part_id,pp.id, pa.min_num as min_num, pp.cct_ref as cct_ref, pa.code as code, pa.old_code as old_code,
				pa.name as name, pa.ptype, pa.name_rus as name_rus, pa.price as price, pp.num as num,
				pa.ptype as ptype, pa.type as type, p.model as model_name, v.name as vendor_name, pa.mktel_has as available
			  FROM `parts` pa
			  LEFT JOIN `phones_parts` pp ON pp.part_id = pa.id
			  LEFT JOIN `phones` p ON pp.phone_id = p.id
			  LEFT JOIN `vendors` v ON pa.vendor_id = v.id ' . $where . '
			  ORDER BY pa.code
			  LIMIT ' . ($page * $pp) . ', ' . $pp;
		return $this->db->query($q, $params)->result_array();
	}

	function countSearchParts($query, $parameter)
	{
		$where = ' WHERE ';
		if($parameter == 'models' )
		{
			$where .= ' p.model LIKE ?';
			$params = '%' . $query . '%';
		}

		if($parameter == 'part_name' )
		{
			$where .= ' pa.name LIKE ? OR pa.name_rus LIKE ?';
			$params = array('%' . $query . '%', '%' . $query . '%');
		}

		if($parameter == 'part_code' )
		{
			$where .= ' pa.code LIKE ?';
			$params = array('%' . $query . '%');
		}

		$q = 'SELECT COUNT(*) as num
			  FROM `parts` pa
			  LEFT JOIN `phones_parts` pp ON pp.part_id = pa.id
			  LEFT JOIN `phones` p ON pp.phone_id = p.id
			  LEFT JOIN `vendors` v ON p.vendor_id = v.id ' . $where;
		return $this->db->query($q, $params)->row()->num;
	}

	function moveParts($data)
	{
		$q = 'UPDATE `phones_parts`
			  SET phone_id  = ?
			  WHERE part_id IN(?)';
		$this->db->query($q, array($data['model_id'], $data['part_id']));
		return true;
	}

	function changePn($new_pn, $old_pn, $v)
	{
		// get existing parts with this pn
		$parts = $this->getPartsByVendor_Code($old_pn, $v);
		if (count($parts) == 0) return false;
		$ids = array_map('narrow_to_id_field_only', $parts);
		$q = '
			UPDATE parts
			SET
				old_code = ?,
				code = ?
			WHERE
				id IN (' . implode(', ', $ids) . ')';
		if ($this->db->query($q, array($old_pn, $new_pn))) {
			return array(
				'old' => $old_pn,
				'new' => $new_pn,
				'ids' => $ids
			);
		} else {
			return false;
		}
	}
}