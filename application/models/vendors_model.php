<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendors_model extends CI_Model
{
	const TABLE = 'vendors';
	const PER_PAGE = 25;
	const SAVE_SUCCESS = 'Вендор успешно сохранен';
	const CREATE_SUCCESS = 'Вендор успешно создан';
	const REMOVE_SUCCESS = 'Вендор успешно удален';
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

	public function getPage($n = 0)
	{
		return $this->db->get(self::TABLE, $n * self::PER_PAGE, self::PER_PAGE)->result_array();
	}

	public function get($id)
	{
		return $this->db->where('id', $id)->get(self::TABLE, 1)->row_array();
	}

	public function getAll($format = 'array', $format_options = false)
	{
		$this->db->order_by('name', 'ASC');
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
				if ($format_options !== false) {
					$selected = $row['id'] == $format_options['selected'] ? ' selected="selected"' : '';
				} else {
					$selected = '';
				}
				$html .= '<option value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</option>';
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

}