<?php

class Settings extends MY_Controller {
	function  __construct() {
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
	}

	public function index(){
		$template = array(
			'title'	=> 'Настройки',
			'js'	=> array('apanel/settings/index.js'),
			'body'	=> $this->load->view(
								'pages/settings/index',
								array(
									'config' => parse_ini_file($this->config->item('ini_path') . 'settings.ini')
								),
								true),
		);
		Modules::run('pages/_return_ap_page', $template);
	}

	public function save(){
		$val = array(
				'currency_eur' => floatval($this->input->post('currency_eur')),
				'cache_enabled' => intval($this->input->post('cache_enabled')) == 0 ? 0 : 1,
				'cache_live_time' => intval($this->input->post('cache_live_time')),
				'per_page' => intval($this->input->post('per_page')),
			);
		write_ini_file($val, $this->config->item('ini_path') . 'settings.ini');
		// update cache for settings file
		$this->cache->delete('ini_config');
		$this->cache->save('ini_config', $val, 3000);
		redirect('/apanel/settings/');
	}
}
