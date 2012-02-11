<?php

class Basket extends My_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function exel() {
		$dataController = array();
		$this->load->model('basket_model');
		$basket = $this->basket_model->getBasket();
		if (!$basket) exit;
		$Currency_model = $this->load->model('Currency_model');
		$pExcel = new PHPExcel();
		$pExcel->setActiveSheetIndex(0);
		$aSheet = $pExcel->getActiveSheet();
		$aSheet->setTitle('Первый лист');
		$aSheet->setCellValueByColumnAndRow(0,1,'Тип');
		$aSheet->setCellValueByColumnAndRow(1,1,'Парт-номер');
		$aSheet->setCellValueByColumnAndRow(2,1,'Описание(eng)');
		$aSheet->setCellValueByColumnAndRow(3,1,'Описание(рус)');
		$aSheet->setCellValueByColumnAndRow(4,1,'Кол-во');
		$aSheet->setCellValueByColumnAndRow(5,1,'Цена сум, грн');
		$aSheet->setCellValueByColumnAndRow(6,1,'Цена за единицу товара, грн');
		$row=2;
		$totalPriceGRN = 0;
		$totalAmount = 0;
		foreach ($basket as $c) {
			$column = 0;
			$c['price']= $c['amount'] * $Currency_model->convert('eur','hrn',$c['price']);
			$totalPriceGRN = $totalPriceGRN + $c['price'];
			$totalAmount = $totalAmount + $c['amount'];
			foreach ($c as $key=>$value) {
				if ($column<=6){
					$aSheet->setCellValueByColumnAndRow($column,$row,$value);
					$column++;
				}
			}
			$row++;
		}
		$aSheet->setCellValueByColumnAndRow(3,$row,'итого: ');
		$aSheet->setCellValueByColumnAndRow(4,$row,$totalAmount);
		$aSheet->setCellValueByColumnAndRow(5,$row,$totalPriceGRN);
		$objWriter = new PHPExcel_Writer_Excel5($pExcel);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="rate.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit;
	}

	public function index() {
		$dataController = array();
		$this->load->model('basket_model');
		$basket = $this->basket_model->getBasket();
		$dataController['basket'] = $basket;
		$user = $this->session->all_userdata();
		$dataController['user_id'] =  isset($user['user_id']) ? $user['user_id'] : 0;
		$dataController['parts_code'] = $this->input->get('parts_code');
		$dataController['name'] = $this->input->get('name');
		$dataController['name_rus'] = $this->input->get('name_rus');
		
		$data = array(
			'title' 		=> 'Корзина' ,
			'description' 	=> '',
			'keywords' 		=> '',
			'css'			=> array('jquery.tablesorter.blue.css'),
			'js'			=> array('site/parts.js', '/libs/jquery.tablesorter.min.js'),
			'body' 			=> $this->load->view('pages/basket/index', $dataController, true),
		);
		Modules::run('pages/_return_page', $data);
	}

	public function order() {		
		$this->load->model('basket_model');
		$basket = $this->basket_model->saveOrder();
		redirect('basket/');
	}
	
	public function InsertIntoBasket() {
		$basket = $this->load->model('basket_model');
		$basket->InsertIntoBasket();
	}
		
	public function removeFromBasket() {
		$basket = $this->load->model('basket_model');
		$basket->removeFromBasket();
	}
	public function sendAmount() {
		$basket = $this->load->model('basket_model');
		$basket->sendAmount();
	}
	public function count() {
		$this->load->model('basket_model');
		$this->basket_model->saveCount();
		redirect('basket/');
	}
}