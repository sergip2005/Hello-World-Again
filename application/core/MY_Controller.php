<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
	function __construct() {
		parent::__construct();

		$this->load->library('session');

		//load fireignition
		$this->load->config('fireignition');

		if ($this->config->item('fireignition_enabled')) {
			if (floor(phpversion()) < 5) {
				log_message('error', 'PHP 5 is required to run fireignition');
			} else {
				$this->load->library('firephp');
				$this->load->helper('my_fireignition');
			}
		} else {
			$this->load->library('firephp_fake');
			$this->firephp =& $this->firephp_fake;
		}

		//what to load in development enveronment
		if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
			//load firephp enhanced profiler
			$sections = array(
				'benchmarks'		=> TRUE, //Elapsed time of Benchmark points and total execution time	TRUE
				'config'			=> FALSE,//CodeIgniter Config variables	TRUE
				'controller_info'	=> FALSE,//The Controller class and method requested	TRUE
				'get'				=> FALSE,//Any GET data passed in the request	TRUE
				'http_headers'		=> FALSE,//The HTTP headers for the current request	TRUE
				'memory_usage'		=> TRUE, //Amount of memory consumed by the current request, in bytes	TRUE
				'post'				=> FALSE,//Any POST data passed in the request	TRUE
				'queries'			=> FALSE,//Listing of all database queries executed, including execution time	TRUE
				'uri_string'		=> FALSE //The URI of the current request	TRUE
			);
			$this->output->set_profiler_sections($sections);
			$this->output->enable_profiler(FALSE);
		}
	}
}
?>