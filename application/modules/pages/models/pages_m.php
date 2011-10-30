<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_m extends CI_Model {

	//var $title   = '';
	//$this->title;

	function __construct()
	{
		parent::__construct();
	}

	function get_page($uri)
	{
		$r = $this->db->query('SELECT * FROM `pages` WHERE `uri` = ' . $this->db->escape($uri) . ' LIMIT 1');
		if ($r->num_rows() > 0) {
			return $r->row_array();
		} else {
			return false;
		}
	}

	/**
	 * type -> static, dynamic, all
	 * limit -> num
	 * uri -> string
	 * id -> int
	 * 
	 */
	function get_pages ($where = false, $params = false) {
		if ($where !== false) {
			$this->db->where($where);
		}

		$limit = isset($params['limit']) ? intval($params['limit']) : false;
		if ($limit !== false) {
			$this->db->limit($limit);
		}
		return $this->db->where('removed', 0)->get('pages')->result_array();
	}

}