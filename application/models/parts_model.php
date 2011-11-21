<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parts_model extends CI_Model
{
	public $partType = array(
		'cabinet' => 'c',
		'solder' => 's',
	);

	public $partTypeName = array(
		'c' => 'корпусной',
		's' => 'паечный'
	);

	public $partFields = array(
		'name', 'vendor_id', 'type',
		'ptype', 'code', 'name_rus',
		'url', 'mktel_has', 'price', 'min_num'
	);

	public function getOrCreate($name, $vendor){
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

	public function updateOrCreate($rowData, $sheetData){
		$this->load->model('currency_model');

		$partData = array();

		// save part data
		foreach ($rowData as $n => $v) {
			if (in_array($n, $this->partFields)) {
				$partData[$n] = $v;
			}
		}
		$partData['vendor_id'] = $sheetData['vendor_id'];
		if (isset($this->partType[$sheetData['type']])) {
			$partData['type'] = $this->partType[$sheetData['type']];
		}
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
				// @TODO add money convertion
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

		if ($id > 0) { // update
			$this->db->where('id', $id)->update('parts', $data);
		} else { // insert
			$this->db->insert('parts', $data);
			$id = $this->db->insert_id();
		}
		return $id;
	}

	function getPartsByNumber($number)
	{
		$parts = $this->db->query('SELECT * FROM parts WHERE code = ? ',array($number))->result_array();
		return $parts;
	}
	function searchParts($model, $parameter)
	{
		$q = 'SELECT
			  pp.id, pa.min_num as min_num, pp.cct_ref as cct_ref, pa.code as code, pa.name as name, pa.ptype,
			  pa.name_rus as name_rus, pa.price as price, pp.num as num, pa.ptype as ptype, pa.type as type, p.model as model, v.name as vendor
			  FROM `phones_parts` pp
			  LEFT JOIN `parts` pa ON pp.part_id = pa.id
			  LEFT JOIN `phones` p ON pp.phone_id = p.id
			  LEFT JOIN `vendors` v ON p.vendor_id = v.id
			  WHERE p.model LIKE ?';
		return $this->db->query($q, $model . '%')->result_array();
	}
}
