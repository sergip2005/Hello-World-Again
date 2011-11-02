<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parts extends MY_Controller {

	private $_m;

	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->_m = $this->load->model('parts_model');
	}

	/**
	 *
	 */
	public function index()
	{
		$template = array(
			'title'			=> '',
			'body'			=> $this->load->view('pages/parts/index', array(), true),
		);

		Modules::run('pages/_return_ap_page', $template);
	}
}
 