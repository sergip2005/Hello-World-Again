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
		$vendors  = $this->_m->getAll();
		$template = array(
			'title'			=> '',
			'description'	=> '',
			'keywords'		=> '',
			'top_menu'		=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/top_menu', '', true),
			'user_menu'		=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/user_menu', '', true),
			'body'			=> $this->load->view('pages/vendors/index', array('vendors' => $vendors), true),
			'bottom_menu'	=> $this->load->view($this->config->item('layout_ap_dir') . 'partials/bottom_menu', '', true),
		);
	
		Modules::run('pages/_return_ap_page', $template);
	}

	public function save()
	{
        $id = intval($this->input->post('id'));
        //ob_start();
        $name = preg_replace('/[^а-яА-Яa-zA-Z0-9_\.\-\/ ]/', '', $this->input->post('name'));
        $data = array('name' => $name);

        $data['id'] = $this->_m->save($id, $data );
        if ($data['id'] > 0) {
            $this->output->set_output(json_encode(array(
                'status'  => 1,
                'item'    => $data,
                'message' => 'SAVE SUCCESS'
				)));
        }else {
			echo $this->output->set_output(json_encode(array('status' => 0, 'error' => 'APP_SUBMIT_ERROR')));
		}
	}

    public function get()
	{
        $id = intval($this->input->post('id'));
        ob_start();
        if ($id > 0) {
			$data = $this->_m->get($id);
			echo json_encode(array(
                'status'  => 1,
                'item'    => $data,
                'message' => 'SAVE SUCCESS'
				));
		} else {
			echo json_encode(array('status' => 0, 'error' => 'APP_SUBMIT_ERROR'));
		}
	}
    public function remove()
	{
        $id = intval($this->input->post('id'));
        ob_start();
        if ($this->_m->remove($id)) {
			echo json_encode(array('status' => 1, 'message' => 'REMOVE_SUCCESS'));
		} else {
			echo json_encode(array('status' => 0, 'error' => 'APP_SUBMIT_ERROR'));
		}

    }
    public function checkbox()
    {
        $id = intval($this->input->post('id'));

        $data = array('show' => $this->input->post('show') == 0 ? '1' : '0');
        ob_start();
        if ($this->_m->checkbox($id, $data)) {
			echo json_encode(array('status' => 1, 'message' => 'change_SUCCESS'));
		} else {
			echo json_encode(array('status' => 0, 'error' => 'APP_SUBMIT_ERROR'));
		}
    }

}