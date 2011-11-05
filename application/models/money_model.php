<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Money_model extends CI_Model
{
	public $rates;
	public $priceFields = array('price_eur', 'price_dol', 'price_hrn');

	function __construct(){
		$this->rates = array(
				'price_hrn' => 1,
				'price_eur' => 11.2,
				'price_dol' => 8.04
			);
	}

	public function convert ($what, $to, $amount) {
		return ( $this->rates[$what] / $this->rates[$to] ) * $amount;
	}

}