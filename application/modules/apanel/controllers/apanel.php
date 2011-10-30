<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apanel extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
	}

	/**
	 * 
	 */
	public function index()
	{
		$data = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'top_menu'		=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/top_menu', '', true),
			'user_menu'		=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/user_menu', '', true),
			'body'			=> $this->load->view('index', '', true),
			'bottom_menu'	=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/bottom_menu', '', true),
		);
		Modules::run('pages/_return_ap_page', $data);
	}

}