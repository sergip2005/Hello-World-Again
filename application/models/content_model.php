<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_model extends CI_Model
{
	const TABLE = 'pages';
		
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
        return $this->db->where('id', $id)->get(self::TABLE, 1)->row_array();
    }

	public function getAllPages()
	{
		return $this->db->get(self::TABLE)->result_array();
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