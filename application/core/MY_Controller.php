<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * @property CI_DB_active_record $db
 * @property CI_DB_forge $dbforge
 * @property CI_Benchmark $benchmark
 * @property CI_Calendar $calendar
 * @property CI_Cart $cart
 * @property CI_Config $config
 * @property CI_Controller $controller
 * @property CI_Email $email
 * @property CI_Encrypt $encrypt
 * @property CI_Exceptions $exceptions
 * @property CI_Form_validation $form_validation
 * @property CI_Ftp $ftp
 * @property CI_Hooks $hooks
 * @property CI_Image_lib $image_lib
 * @property CI_Input $input
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Log $log
 * @property CI_Model $model
 * @property CI_Output $output
 * @property CI_Pagination $pagination
 * @property CI_Parser $parser
 * @property CI_Profiler $profiler
 * @property CI_Router $router
 * @property CI_Session $session
 * @property CI_Sha1 $sha1
 * @property CI_Table $table
 * @property CI_Trackback $trackback
 * @property CI_Typography $typography
 * @property CI_Unit_test $unit_test
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $user_agent
 * @property CI_Validation $validation
 * @property CI_Xmlrpc $xmlrpc
 * @property CI_Xmlrpcs $xmlrpcs
 * @property CI_Zip $zip
 * @property CI_Javascript $javascript
 * @property CI_Jquery $jquery
 * @property CI_Utf8 $utf8
 * @property CI_Security $security
 *
 * @property Ion_auth $ion_auth
 * @property Import_model $import_model
 * @property Phones_model $phones_model
 * @property Parts_model $parts_model
 * @property Regions_model $regions_model
 * @property Import_data_tpl_model $import_data_tpl_model
 * @property Currency_model $currency_model
 */

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

		// parse settings.ini file to CI config
		$this->_load_ini_config();
	}

	private function _load_ini_config(){
		$dConf = parse_ini_file($this->config->item('ini_path') . 'settings.ini');
		foreach ($dConf as $key => $val) {
			$this->config->set_item($key, $val);
		}
	}
}
?>