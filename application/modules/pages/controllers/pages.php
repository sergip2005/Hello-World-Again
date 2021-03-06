<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('pages_m');
	}

	public function index()
	{
		$this->_return_404('pages');
	}

	public function _serve_page($uri)
	{
		$page = $this->pages_m->get_page($uri);
		if ($page === false) {
			//die 404
			$this->_return_404($uri);
		} else {
			//serve page
			$this->_return_page($page);
		}
	}

	public function _return_page($data)
	{
		// get phones tree
		$this->load->model('phones_model');
		$catalog = $this->phones_model->getModelsTree();

		$search = array();
		if (array_key_exists('search_params', $data)) {
			$search['search_params'] = $data['search_params'];
		}

		$data['title'] .= ($data['title'] ? ' - ' : '' ) . 'originalspareparts.com.ua';
		$data['top_menu'] = $this->load->view($this->config->item('layout_dir') . 'partials/top_menu', '', true);
		$data['user_menu'] = $this->load->view($this->config->item('layout_dir') . 'partials/user_menu', '', true);
		$data['bottom_menu'] = $this->load->view($this->config->item('layout_dir') . 'partials/bottom_menu', '', true);
		$data['models_menu'] = $this->load->view($this->config->item('layout_dir') . 'partials/models_menu', array('catalog' => $catalog, 'model_obj' => isset($data['data']) ? $data['data']['model'] : array('model' => '', 'id' => 0), 'vendor_obj' => isset($data['data']) ? $data['data']['vendor'] : array('name' => '', 'id' => 0)), true);
		$data['search'] = $this->load->view($this->config->item('layout_dir') . 'partials/search', '', true);
		$data['basket'] = $this->load->view($this->config->item('layout_dir') . 'partials/basket', '', true);

		$this->load->view($this->config->item('layout_dir') . 'index', $data);
	}

	public function _return_ap_page($data)
	{
		$data['title'] .= ($data['title'] ? ' - ' : '' ) . 'Админ панель';
		$data['bottom_menu'] = $this->load->view($this->config->item('layout_ap_dir') . 'partials/bottom_menu', '', true);
		$data['top_menu'] = $this->load->view($this->config->item('layout_ap_dir') . 'partials/top_menu', '', true);
		$data['user_menu'] = $this->load->view($this->config->item('layout_ap_dir') . 'partials/user_menu', '', true);
		$data['flashmessage'] = $this->session->flashdata('message');

		$this->load->view($this->config->item('layout_ap_dir') . 'index', $data);
	}

	public function _return_404($uri)
	{
		header('HTTP/1.0 404 Not Found');
		$this->load->view($this->config->item('layout_dir') . '404', array('html' => $uri));
	}
}