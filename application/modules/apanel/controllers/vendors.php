<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendors extends MY_Controller {

	private $_m;

	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->_m = $this->load->model('vendors_model');
	}

	/**
	 * 
	 */
	public function index()
	{
		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'top_menu'		=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/top_menu', '', true),
			'user_menu'		=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/user_menu', '', true),
			'body'			=> $this->load->view('pages/vendors/index', '', true),
			'bottom_menu'	=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/bottom_menu', '', true),
		);
		Modules::run('pages/_return_ap_page', $template);
	}

	public function save()
	{

	}
}