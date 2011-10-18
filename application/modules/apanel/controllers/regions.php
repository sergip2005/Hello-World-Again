<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Regions extends MY_Controller {

	private $_m;
	
	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login');
		}
		$this->_m = $this->load->model('regions_model');
	}

	/**
	 * 
	 */
	public function index()
	{
		$regions  = $this->_m->getAll();
		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'js'			=> array('js' => 'apanel/regions.js'),
			'body'			=> $this->load->view('pages/regions/index', array('regions' => $regions), true),
		);
	
		Modules::run('pages/_return_ap_page', $template);
	}

	public function save()
	{
        $id = intval($this->input->post('id'));
        $name = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/', '', $this->input->post('name'));
        $data = array('name' => $name);

        $data['id'] = $this->_m->save($id, $data );
        if ($data['id'] > 0) {
            $this->output->set_output(json_encode(array(
                'status'  => 1,
                'item'    => $data,
                'message' => Regions_model::SAVE_SUCCESS
				)));
        }else {
			echo json_encode(array('status' => 0, 'error' => Regions_model::APP_SUBMIT_ERROR));
		}
	}

    public function get()
	{
        $id = intval($this->input->post('id'));
        if ($id > 0) {
			$data = $this->_m->get($id);
			$this->output->set_output( json_encode(array(
                'status'  => 1,
                'item'    => $data,
                'message' => Regions_model::SAVE_SUCCESS
				)));
		} else {
			$this->output->set_output(json_encode(array('status' => 0, 'error' => Regions_model::APP_SUBMIT_ERROR)));
		}
	}
    public function remove()
	{
        $id = intval($this->input->post('id'));
        if ($this->_m->remove($id)) {
			$this->output->set_output(json_encode(array('status' => 1, 'message' => Regions_model::REMOVE_SUCCESS)));
		} else {
			$this->output->set_output(json_encode(array('status' => 0, 'error' => Regions_model::APP_SUBMIT_ERROR)));
		}

    }

	public function set_default()
    {
        $id = intval($this->input->post('id'));

        $data = array('show' => $this->input->post('show') == 0 ? '1' : '0');

        if ($this->_m->set_default($id, $data)) {
			$this->output->set_output(json_encode(array('status' => 1, 'message' => Regions_model::SAVE_SUCCESS)));
		} else {
			$this->output->set_output(json_encode(array('status' => 0, 'error' => Regions_model::APP_SUBMIT_ERROR)));
		}
    }

}