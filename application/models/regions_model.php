<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Regions_model extends CI_Model
{
	const TABLE = 'regions';
	const SAVE_SUCCESS = 'Регион успешно сохранен';
	const CREATE_SUCCESS = 'Регион успешно создан';
	const REMOVE_SUCCESS = 'Регион успешно удален';
	const APP_SUBMIT_ERROR = 'Извините, но возникла проблема с обработкой полученных данных. Пожалуйста, попробуйте еще раз.';

	public function save($id, $data)
	{
		$error = false;

		if (isset($id) && intval($id) > 0) {
			// update
			if (!$this->db->where('id', $id)->update(self::TABLE, $data)) {
				$error = true;
			}
		} else {
			// save
			if (!$this->db->insert(self::TABLE, $data)) {
				$error = true;
			}
			$id = $this->db->insert_id();
		}
		return $error ? false : $id;
	}

    public function get($id)
	{
       return $this->db->where('id', $id)->get(self::TABLE)->row_array();

	}

	public function getAll($format = 'array')
	{
		$res = $this->db->get(self::TABLE)->result_array();

		if ($format == 'ul') {
			$html = '<ul>';
			foreach ($res as $row) {
				$html .= '<li data-id="' . $row['id'] . '">' . $row['name'] . '</li>';
			}
			$html .= '</ul>';
			return $html;
		}

		if ($format == 'select') {
			$html = '';
			foreach ($res as $row) {
				$html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</li>';
			}
			return $html;
		}

		return $res;
	}

	public function remove($id)
	{
		$error = false;
		if (isset($id) && intval($id) > 0) {
			if (!$this->db->where('id', $id)->delete(self::TABLE)) {
				$error = true;
			}
		} else {
		 $error = true;
		}
		return $error ? false : true;
	}

	public function set_default($id)
	{
		$error = false;
		if (isset($id) && intval($id) > 0) {
			if (!$this->db->update(self::TABLE, array('default' => 0)) || !$this->db->where('id', $id)->update(self::TABLE, array('default' => 1))) {
				$error = true;
			}
		} else {
		 $error = true;
		}

		return $error ? false : true;
	}

}