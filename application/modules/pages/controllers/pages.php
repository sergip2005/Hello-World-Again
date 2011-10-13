<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('pages_m');
	}

	/**
	 * 
	 */
	public function index()
	{
		$this->_return_404('pages');
	}

	/**
	 * 
	 */
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

	/**
	 * 
	 */
	public function _return_page($data)
	{
		$this->load->view($this->config->item('layout_dir') . 'index', $data);
	}

	public function _return_ap_page($data)
	{
		$data['title'] .= ($data['title'] ? ' - ' : '' ) . 'Админ панель';
		$this->load->view($this->config->item('layout_ap_dir') . 'index', $data);
	}

	/**
	 * 
	 */
	public function _return_404($uri)
	{
		header('HTTP/1.0 404 Not Found');
		$this->load->view($this->config->item('layout_dir') . '404', array('html' => $uri));
	}
}