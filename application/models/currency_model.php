<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_model extends CI_Model
{
	public $rates;
	public $priceFields = array('price_eur', 'price_hrn');
	public $base = 'price_eur';

	function __construct(){
		// @TODO get this from db
		$this->rates = array(
				'hrn' => 1,
				'eur' => $this->config->item('currency_eur'),// base currency
			);
	}

	public function convert($what, $to, $amount) {
		if ($what == $to) {
			return round($amount, 2);// don't convert nothing
		} else {
			return round(($this->rates[$what] / $this->rates[$to]) * $amount, 2);
		}
	}

}