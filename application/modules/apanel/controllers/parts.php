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

	public function index()
	{
		$this->load->model('vendors_model');
		$this->load->model('phones_model');

		$data = array(
			'vendors' => $this->vendors_model->getAll(),
		);

		$template = array(
			'title'	=> 'Запчасти',
			'css'	=> array('uploadify.css'),
			'js'	=> array('apanel/parts/index.js', 'apanel/uploadify-v2.1.4/swfobject.js', 'apanel/uploadify-v2.1.4/jquery.uploadify.v2.1.4.min.js'),
			'body'	=> $this->load->view('pages/parts/index', $data, true),
		);

		Modules::run('pages/_return_ap_page', $template);
	}

	public function search()
	{
		$this->load->model('phones_model');
		$this->load->model('parts_model');

		$search_params['vendor_id'] = (int)$this->input->post('vendor_id');
		$search_params['model_id'] = $this->input->post('model_id');
		$search_params['page'] = get_posted_page($this->input->post('page'));

		$search_params['query'] = sanitate_input_string(urldecode($this->input->post('query')));
		$search_params['parameter'] = sanitate_input_string($this->input->post('param'));

		$search_params['pagination'] = array( 'page' => $search_params['page'] );

		if (!in_array($search_params['model_id'], array('all', 'none'))) {
			$search_params['model_id'] = intval($search_params['model_id']);

			if ($search_params['model_id'] <= 0) {
				if (isset($search_params['query']) && isset($search_params['parameter'])) {

					switch ($search_params['parameter']){
						case 'parts_code':
							$parts = $this->parts_model->getPartsByCode($search_params['query'], $search_params['page']);
							$search_params['pagination']['items'] = $this->parts_model->countGetPartsByCode($search_params['query']);
						break;
						case 'model_name':
							$parts = $this->parts_model->searchParts($search_params['query'], 'models', $search_params['page']);
							$search_params['pagination']['items'] = $this->parts_model->countSearchParts($search_params['query'], 'models');
						break;
						case 'parts_name':
							$parts = $this->parts_model->searchParts($search_params['query'], 'part_name', $search_params['page']);
							$search_params['pagination']['items'] = $this->parts_model->countSearchParts($search_params['query'], 'part_name');
						break;
					}

					if (count($parts) > 0) {
						calculatePaginationParams($search_params['pagination']);
						$this->output->set_output(json_encode(array( 'status' => 1, 'data' => array('parts' => $parts), 'pagination' => $search_params['pagination'])));
						return;
					} else {
						$this->output->set_output(json_encode(array( 'status' => 0 )));
						return;
					}

				} else {
					$this->output->set_output(json_encode(array( 'status' => 0 )));
					return;
				}
			}
		}

		$parts = $this->phones_model->getParts($search_params['vendor_id'], $search_params['model_id'], 'all', false, $search_params['page']);
		$search_params['pagination']['items'] = $this->phones_model->countGetParts($search_params['vendor_id'], $search_params['model_id'], 'all');
		if (!empty($parts) && count($parts) > 0) {
			calculatePaginationParams($search_params['pagination']);
			$this->output->set_output(json_encode(array( 'status' => 1, 'data' => $parts, 'pagination' => $search_params['pagination'] )));
		} else {
			$this->output->set_output(json_encode(array( 'status' => 0 )));
		}
	}

	public function move()
	{
		$this->load->model('parts_model');

		$move['model_id'] = $this->input->post('model_id');
		$move['part_id'] = $this->input->post('parts_id');

		if (isset($move['model_id']) && isset($move['part_id'])) {
			$move = $this->parts_model->moveParts($move);
			if ($move) {
				$this->output->set_output(json_encode(array( 'status' => 1 )));
				return;
			} else {
				$this->output->set_output(json_encode(array( 'status' => 0 )));
				return;
			}
		} else {
			$this->output->set_output(json_encode(array( 'status' => 0 )));
			return;
		}
	}

	public function change_pn()
	{
		$ch_pn = (string)$this->input->post('change_pn');
		$pn = (string)$this->input->post('pn');
		$v_id = (int)$this->input->post('vendor_id');

		$this->load->model('parts_model');
		$res = $this->parts_model->changePn($ch_pn, $pn, $v_id);
		if ($res !== false) {
			$this->output->set_output(json_encode(array('status' => 1, 'result' => $res)));
		} else {
			$this->output->set_output(json_encode(array('status' => 0)));
		}
	}

	public function save()
	{
		//die(json_encode($_POST));

		$this->load->model('phones_model');

		$part_id = (int)$this->input->post('part_id');//"1033"
		if ($part_id > 0) {
			$data['mktel_has'] = (int)$this->input->post('available');//"1"
			$data['min_num'] = (int)$this->input->post('min_num');//"1"
			$data['price'] = (float)$this->input->post('price');//"0.00"
			$data['price1'] = (float)$this->input->post('price1');//"0.00"
			$data['price2'] = (float)$this->input->post('price2');//"0.00"
			$data['ptype'] = (string)$this->input->post('ptype');//""
			$data['type'] = in_array($this->input->post('type'), $this->parts_model->partType) ? $this->input->post('type') : 'c';//"c"
			$data['name'] = (string)$this->input->post('name');//"A COVER ASSEMBLY MAT BLACK"
			$data['name_rus'] = (string)$this->input->post('name_rus');//""
			$this->parts_model->save($part_id, $data);
		}

		$id = (int)$this->input->post('id');//"185"
		if ($id > 0) {
			$data2['num'] = (int)$this->input->post('num');//"1"
			$data2['cct_ref'] = (string)$this->input->post('cct_ref');//"I0001"
			$this->phones_model->savePart($id, $data2);
		}

		//$data['code'] = $this->input->post('code');//"0253378"
		//$data['old_code'] = $this->input->post('old_code');//"9999999"
	}
}