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
}
