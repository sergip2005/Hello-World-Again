<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendors_model extends CI_Model
{
	const TABLE = 'vendors';
	const PER_PAGE = 25;

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
        return $this->db->where('id', $id)->limit(1)->get(self::TABLE)->row_array();
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
			$html = '<select name="vendors">';
			foreach ($res as $row) {
				$html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</li>';
			}
			$html .= '</select>';
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

    public function checkbox($id, $data)
    {
        $error = false;
        if (isset($id) && intval($id) > 0) {
            if (!$this->db->where('id', $id)->update(self::TABLE, $data)) {
				$error = true;
			}
        }
        return $error ? false : true;
    }
   
}