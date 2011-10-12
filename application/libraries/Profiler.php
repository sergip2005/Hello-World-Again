<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Profiler Class
 *
 * This class enables you to display benchmark, query, and other data
 * in order to help with debugging and optimization.
 *
 * Note: At some point it would be good to move all the HTML in this class
 * into a set of template files in order to allow customization.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/profiling.html
 */
class CI_Profiler {

	var $CI;

	protected $_available_sections = array(
				'benchmarks',
				'get',
				'memory_usage',
				'post',
				'uri_string',
				'controller_info',
				'queries',
				'http_headers',
				'config'
			);

	public function __construct($config = array()) {
		$this->CI =& get_instance();
		$this->CI->load->language('profiler');
		
		$this->CI->load->helper('my_fireignition_helper');

		// default all sections to display
		foreach ($this->_available_sections as $section) {
			if ( ! isset($config[$section])) {
				$this->_compile_{$section} = TRUE;
			}
		}

		$this->set_sections($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Set Sections
	 *
	 * Sets the private _compile_* properties to enable/disable Profiler sections
	 *
	 * @param	mixed
	 * @return	void
	 */
	public function set_sections($config) {
		foreach ($config as $method => $enable) {
			if (in_array($method, $this->_available_sections)) {
				$this->_compile_{$method} = ($enable !== FALSE) ? TRUE : FALSE;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Auto Profiler
	 *
	 * This function cycles through the entire array of mark points and
	 * matches any two points that are named identically (ending in "_start"
	 * and "_end" respectively).  It then compiles the execution times for
	 * all points and returns it as an array
	 *
	 * @return	array
	 */
	protected function _compile_benchmarks() {
		$profile = array();
		foreach ($this->CI->benchmark->marker as $key => $val) {
			// We match the "end" marker so that the list ends
			// up in the order that it was defined
			if (preg_match("/(.+?)_end/i", $key, $match)) {
				if (isset($this->CI->benchmark->marker[$match[1].'_end']) AND isset($this->CI->benchmark->marker[$match[1].'_start'])) {
					$profile[$match[1]] = $this->CI->benchmark->elapsed_time($match[1].'_start', $key);
				}
			}
		}

		// Build a table containing the profile data.
		// Note: At some point we should turn this into a template that can
		// be modified.  We also might want to make this data available to be logged

		fb($this->CI->lang->line('profiler_benchmarks'));

		foreach ($profile as $key => $val) {
			$key = ucwords(str_replace(array('_', '-'), ' ', $key));
			fb('   - ' . $key . ': ' . $val);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Compile Queries
	 *
	 * @return	string
	 */
	protected function _compile_queries() {
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object) {
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') ) {
				$dbs[] = $CI_object;
			}
		}

		if (count($dbs) == 0) {
			fb($this->CI->lang->line('profiler_queries'));
			fb($this->CI->lang->line('profiler_no_db'));
		}

		foreach ($dbs as $db) {
			fb($this->CI->lang->line('profiler_database') . ': ' . $db->database . ' ' . $this->CI->lang->line('profiler_queries') . ': ' . count($db->queries));

			if (count($db->queries) == 0) {
				fb('   - ' .  $this->CI->lang->line('profiler_no_queries'));
			} else {
				foreach ($db->queries as $key => $val) {
					$time = number_format($db->query_times[$key], 4);
					//$val = highlight_code($val, ENT_QUOTES);
					fb('   - ' .  $time . ': ' . $val);
				}
			}
		}
	}


	// --------------------------------------------------------------------

	/**
	 * Compile $_GET Data
	 *
	 * @return	string
	 */
	protected function _compile_get() {
		fb($this->CI->lang->line('profiler_get_data'));

		if (count($_GET) == 0) {
			fb('   - ' .  $this->CI->lang->line('profiler_no_get'));
		} else {
			foreach ($_GET as $key => $val) {
				if ( ! is_numeric($key)) {
					$key = "'" . $key . "'";
				}

				fb('   - ' .  "_GET[".$key."]");
				if (is_array($val)) {
					fb('      - ', htmlspecialchars(stripslashes(print_r($val, true))));
				} else {
					fb('      - ', htmlspecialchars(stripslashes($val)));
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Compile $_POST Data
	 *
	 * @return	string
	 */
	protected function _compile_post() {
		fb($this->CI->lang->line('profiler_post_data'));

		if (count($_POST) == 0) {
			fb('   - ' .  $this->CI->lang->line('profiler_no_post'));
		} else {
			foreach ($_POST as $key => $val) {
				if ( ! is_numeric($key)) {
					$key = "'" . $key . "'";
				}

				fb('   - ' .  "_POST[" . $key . "]");
				if (is_array($val)) {
					fb('   - ' .  htmlspecialchars(stripslashes(print_r($val, TRUE))));
				} else {
					fb('   - ' .  htmlspecialchars(stripslashes($val)));
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Show query string
	 *
	 * @return	string
	 */
	protected function _compile_uri_string() {
		fb($this->CI->lang->line('profiler_uri_string'));

		if ($this->CI->uri->uri_string == '') {
			fb('   - ' .  $this->CI->lang->line('profiler_no_uri'));
		} else {
			fb('   - ' .  $this->CI->uri->uri_string);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Show the controller and function that were called
	 *
	 * @return	string
	 */
	protected function _compile_controller_info() {
		fb($this->CI->lang->line('profiler_controller_info') . ': ' . $this->CI->router->fetch_class()."/".$this->CI->router->fetch_method());
	}

	// --------------------------------------------------------------------

	/**
	 * Compile memory usage
	 *
	 * Display total used memory
	 *
	 * @return	string
	 */
	protected function _compile_memory_usage () {
		fb($this->CI->lang->line('profiler_memory_usage'));

		if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '') {
			fb('   - ' .  number_format($usage) . ' bytes');
		} else {
			fb('   - ' .  $this->CI->lang->line('profiler_no_memory_usage'));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Compile header information
	 *
	 * Lists HTTP headers
	 *
	 * @return	string
	 */
	protected function _compile_http_headers() {
		fb($this->CI->lang->line('profiler_headers'));

		foreach (array('HTTP_ACCEPT', 'HTTP_USER_AGENT', 'HTTP_CONNECTION', 'SERVER_PORT', 'SERVER_NAME', 'REMOTE_ADDR', 'SERVER_SOFTWARE', 'HTTP_ACCEPT_LANGUAGE', 'SCRIPT_NAME', 'REQUEST_METHOD',' HTTP_HOST', 'REMOTE_HOST', 'CONTENT_TYPE', 'SERVER_PROTOCOL', 'QUERY_STRING', 'HTTP_ACCEPT_ENCODING', 'HTTP_X_FORWARDED_FOR') as $header) {
			$val = (isset($_SERVER[$header])) ? $_SERVER[$header] : '';
			fb ('   - ' . $header . ': ' . $val);
		}

	}

	// --------------------------------------------------------------------

	/**
	 * Compile config information
	 *
	 * Lists developer config variables
	 *
	 * @return	string
	 */
	protected function _compile_config() {
		fb($this->CI->lang->line('profiler_config'));

		foreach ($this->CI->config->config as $config=>$val) {
			if (is_array($val)) {
				$val = print_r($val, TRUE);
			}

			fb('   - ' . $config . ': ' . htmlspecialchars($val));
		}

		fb($output);
	}

	// --------------------------------------------------------------------

	/**
	 * Run the Profiler
	 *
	 * @return	string
	 */
	public function run() {
		$fields_displayed = 0;

		foreach ($this->_available_sections as $section) {
			if ($this->_compile_{$section} !== FALSE) {
				$func = "_compile_{$section}";
				fb('   - ' .  $this->{$func}());
				$fields_displayed++;
			}
		}

		if ($fields_displayed == 0) {
			fb($this->CI->lang->line('profiler_no_profiles'));
		}
	}

}

// END CI_Profiler class

/* End of file Profiler.php */
/* Location: ./system/libraries/Profiler.php */