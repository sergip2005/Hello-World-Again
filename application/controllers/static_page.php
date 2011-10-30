<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Static_page extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function view($uri)
	{
		Modules::run('pages/_serve_page', $uri);
	}
}