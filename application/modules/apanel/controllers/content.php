<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller {

	private $_m;
	
	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->_m = $this->load->model('content_model');
	}

	/**
	 * 
	 */
	public function index()
	{
		$pages  = $this->_m->getAllPages();
		$template = array(
			'title'			=> '',
			'js'			=> array('js' => 'apanel/content.js'),
			'body'			=> $this->load->view('pages/content/index', array('pages' => $pages), true),
		);

		Modules::run('pages/_return_ap_page', $template);
	}

	public function editor()
	{
		$page_id = intval($this->input->post('page_id'));
		$page = array('type' => '');
		if($page_id > 0)
		{
			$page  = $this->_m->get(intval($this->input->post('page_id')));
		}
		$template = array(
			'title'			=> '',
			'js'			=> array('js' => 'apanel/tiny_mce/tiny_mce.js', 'js2' => 'apanel/editor.js'),
			'body'			=> $this->load->view('pages/content/editor', array('page' => $page), true),
		);

		Modules::run('pages/_return_ap_page', $template);
	}

	public function save()
	{
		if($this->input->post('save') == 'Submit'){
			$now = unix_to_human(time(), TRUE, 'eu');
			$id = intval($this->input->post('id'));
			$data = array(
				'body'        => htmlspecialchars_decode($this->input->post('body')),
				'title'       => preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/', '',$this->input->post('title')),
				'uri'         => preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/', '',$this->input->post('uri')),
				'keywords'    => preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\,\-\/ ]/', '',$this->input->post('keywords')),
				'description' => preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\,\-\/ ]/', '',$this->input->post('description')),
				'type'        => intval($this->input->post('type')),
				'last_edited' => $now,
			);
			if(isset($id)) $data['created'] = $now;
			$this->_m->save($id, $data);
		};
		redirect('apanel/content', 'refresh');
	}
}