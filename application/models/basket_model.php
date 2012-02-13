<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Basket_model extends CI_Model
{

	public function InsertIntoBasket() {
		$part_id = intval($this->input->post('part_id'));
		$session_id = $this->session->userdata('session_id');
		$user = $this->session->all_userdata();
		$user_id = isset($user['user_id']) ? $user['user_id'] : 0;
		if ($part_id > 0) {
			if ($user_id > 0) {
				$str ="(b.user_id=$user_id or b.session_id='$session_id')";
			} else {
				$str ="b.session_id='$session_id'";
			}
			$str.= " AND b.part_id=".$part_id;
			$sql = "SELECT b.amount FROM basket b WHERE $str";
			$q = $this->db->query($sql);

			if ($q->num_rows > 0) {
				foreach ($q->result() as $row);
				$amount = $row->amount + intval($this->input->post('amount'));
				$sql = "UPDATE basket b SET b.amount = $amount WHERE $str";
				$this->db->query($sql);
			}
			else {
				$amount = intval($this->input->post('amount'));
				$data = array(
				'part_id' => $part_id ,
				'session_id' => $session_id ,
				'user_id' => $user_id,
				'amount' => $amount
				);
				$this->db->insert('basket', $data);
			}
			$basket = $this->getBasket();
			$count = count($basket);
			$data = array('amount'=>$amount,'count'=>$count);
			echo json_encode($data);
			exit;
		}
	}

	public function removeFromBasket() {
		$id = intval($this->input->post('id'));
		$sql = "DELETE from basket WHERE id = $id";
		$this->db->query($sql);
	}

	public function sendAmount() {
		$id = intval($this->input->post('id'));
		$amount = intval($this->input->post('amount'));
		$sql = "UPDATE basket SET amount='$amount' WHERE id = $id";
		$this->db->query($sql);
	}

	public function getBasket() {
		$data = array();
		$user = $this->session->all_userdata();
		$user_id = isset($user['user_id']) ? $user['user_id'] : 0;
		$session_id = $this->session->userdata('session_id');
		$parts_code = $this->input->get('parts_code');
		$name = $this->input->get('name');
		$name_rus = $this->input->get('name_rus');
		if ($user_id > 0) {
			$str ="(b.user_id=$user_id or b.session_id='$session_id')";
		} else {
			$str ="b.session_id='$session_id'";
		}
		if ($parts_code) {
			$str.= " and code like '%$parts_code%'";
		}
		if ($name) {
			$str.= " and name like '%$name%'";
		}
		if ($name_rus) {
			$str.= " and name_rus like '%$name_rus%'";
		}
		$sql = "
			SELECT
				p.ptype, p.code, p.name, p.name_rus, b.amount,
				p.price, p.price as price_grn, p.id, b.id as basket_id, p.min_num
			FROM basket b
			LEFT JOIN parts p
				ON p.id = b.part_id
			WHERE $str";
		$q = $this->db->query($sql);
		$Currency_model = $this->load->model('Currency_model');
		foreach ($q->result_array() as $row)
		{
			$row['price_grn'] = $Currency_model->convert('eur','hrn',$row['price']);
			$data[] = $row;
		}
		return $data;
	}

	public function saveOrder() {
		$user = $this->session->all_userdata();
		$user_id = isset($user['user_id']) ? $user['user_id'] : 0;
		if ($user_id  and $_POST) {
			$time = time();
			$data = array(
			'user_id' => $user_id,
			'date' => $time,
			'totalPrice'=> $this->input->post('totalPrice'),
			'totalAmount'=> intval($this->input->post('totalAmount')),
			);
			$this->db->insert('orders', $data);
			$order_id = $this->db->insert_id();
			$basket = $this->input->post('basket');
			foreach ($basket as $key => $value) {
				$data = array(
				'part_id' => intval($value['part_id']) ,
				'amount' => intval($value['amount']),
				'order_id' => $order_id ,
				);
				$this->db->insert('order_parts', $data);
			}
			$sql = "DELETE from basket WHERE user_id = $user_id";
			$this->db->query($sql);
		}
	}

	public function saveCount() {
		$basket = $this->input->post('basket');
		foreach ($basket as $value) {
			$data = array(
			'amount' => $value['amount'],
			);
			$this->db->where('id', $value['basket_id'])->update('basket', $data);
		}
	}
}