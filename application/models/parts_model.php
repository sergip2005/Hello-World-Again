<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parts_model extends CI_Model
{
	public $partType = array(
		'cabinet' => 'c',
		'colder' => 's',
	);

	public $partFields = array(
		'name', 'vendor_id', 'type',
		'ptype', 'code', 'name_rus',
		'url', 'mktel_has', 'price', 'min_num'
	);

	public $phonePartFields = array(
		'cct_ref', 'num', 'comment'
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
		$this->load->model('phones_model');

		$partData = array();
		$phonePartData = array();

		// save part data
		foreach ($rowData as $n => $v) {
			if (in_array($n, $this->partFields)) {
				$partData[$n] = $v;
			}
		}
		$partData['vendor_id'] = $sheetData['vendor_id'];
		$partData['type'] = $this->partType[$sheetData['type']];
		$partData['show'] = 1;
		// default vals for min_num
		if (!isset($partData['min_num']) || empty($partData['min_num']) || $partData['min_num'] == 0) {
			$partData['min_num'] = $partData['type'] == 'c' ? 1 : 5;
		}
		// insert or update part
		$pId = $this->save(0, $partData);

		// save phone model data
		foreach ($rowData as $n => $v) {
			if (in_array($n, $this->phonePartFields)) {
				$phonePartData[$n] = $v;
			}
		}
		$phonePartData['phone_id'] = $sheetData['phone_id'];
		$phonePartData['part_id'] = $pId;
		// insert or update phone
		$this->phones_model->save(0, $phonePartData);
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
}