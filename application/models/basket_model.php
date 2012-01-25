<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Basket_model extends CI_Model
{
	const SAVE_SUCCESS = 'Модель успешно сохранена';
	const CREATE_SUCCESS = 'Модель успешно создана';
	const REMOVE_SUCCESS = 'Модель успешно удалена';
	const APP_SUBMIT_ERROR = 'Извините, но возникла проблема с обработкой полученных данных. Пожалуйста, попробуйте еще раз. Возможно имя модели не уникально.';
	public $phonePartFields = array(
	'cct_ref', 'num', 'comment'
	);

	public function InsertIntoBasket() {
		$part_id = intval($_POST['part_id']);
		$session_id = $this->session->userdata('session_id');
		$user = $this->session->all_userdata();
		$user_id = isset($user['user_id']) ? $user['user_id'] : 0;
		if ($part_id>0) {
			$sql = "INSERT INTO basket (part_id,session_id,user_id) values ('$part_id','$session_id','$user_id')";
			$this->db->query($sql);
		}
	}

	public function getBasket() {
		$data = array();
		$user = $this->session->all_userdata();
		$user_id = isset($user['user_id']) ? $user['user_id'] : 0;
		$session_id = $this->session->userdata('session_id');
		if($user_id > 0) {
			$str ="b.user_id=$user_id";
		}
		else {
			$str ="b.session_id='$session_id'";
		}
		$sql = "SELECT p.ptype,p.code,p.name,p.name_rus,p.min_num,p.price,p.id FROM basket b
		LEFT JOIN parts p on p.id=b.part_id
		WHERE $str";
		$q = $this->db->query($sql);
		foreach ($q->result_array() as $row)
		{
			$data[] = $row;
		}
		return $data;
	}
	public function saveOrder() {
		$user = $this->session->all_userdata();
		$user_id = isset($user['user_id']) ? $user['user_id'] : 0;
		if ($user_id) {
			$basket = $this->getBasket();
			$time = time();
			$sql = "INSERT INTO orders (user_id,date) values ('$user_id','$time') ";
			$this->db->query($sql);
			$order_id = mysql_insert_id();
			foreach ($basket as $key => $value) {
				$part_id = $value['id'];
				$sql = "INSERT INTO order_parts (order_id,part_id) values ('$order_id','$part_id')";
				$this->db->query($sql);
			}
			$sql = "DELETE from basket WHERE user_id = $user_id";
			$this->db->query($sql);
		}
	}
}
