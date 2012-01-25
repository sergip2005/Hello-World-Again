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
		$pExcel = new PHPExcel();
		$pExcel->setActiveSheetIndex(0);
		$aSheet = $pExcel->getActiveSheet();
		$aSheet->setTitle('Первый лист');
		$aSheet->setCellValueByColumnAndRow(0,1,'Тип');
		$aSheet->setCellValueByColumnAndRow(1,1,'Парт-номер');
		$aSheet->setCellValueByColumnAndRow(2,1,'Описание(eng)');
		$aSheet->setCellValueByColumnAndRow(3,1,'Описание(рус)');
		$aSheet->setCellValueByColumnAndRow(4,1,'Кол-во');
		$aSheet->setCellValueByColumnAndRow(5,1,'Цена, грн');
		$row=2;
		foreach ($basket as $c) {
			$column = 0;
			foreach ($c as $value) {
				if ($column<=5){
					$aSheet->setCellValueByColumnAndRow($column,$row,$value);
					$column++;
				}
			}
			$row++;
		}
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
		$data = array(
		'title' 		=> 'Корзина: ' ,
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
}
?>