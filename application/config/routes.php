<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// DEFAULT
$route['default_controller'] = 'phones';

//STATIC PAGES
$route['about'] = '/static_page/view/about';
$route['contacts'] = '/static_page/view/contacts';
$route['how-to-order'] = '/static_page/view/how-to-order';
$route['phones/(:any)/(:any)'] = '/phones/parts/$1/$2';

// AUTH
$route['login'] = '/auth/login/';
$route['logout'] = '/auth/logout/';
$route['register'] = '/auth/register/';
$route['change_password'] = '/auth/change_password/';
$route['forgot_password'] = '/auth/forgot_password/';
$route['reset_password'] = '/auth/reset_password/';
$route['activate'] = '/auth/activate/';

//ADMIN PANEL
#$route['killallhumans'] = 'admin/index';
#$route['killallhumans/pages/recycle_bin/'] = 'admin/a_pages/recycle_bin/';
#$route['killallhumans/(:any)'] = 'admin/$1';
#$route['killallhumans/(:any)/(:any)'] = 'admin/$1/$2';

// ERRORS
$route['404_override'] = '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */