<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

#home ---------
$route['(?i)home/(:any)'] = 'home/$1';
$route['(?i)home'] = 'home';
#------

#listings
$route['(?i)listings/property'] = 'listings';
$route['(?i)listings/land'] = 'listings/land';
$route['(?i)listings_single/(:num)'] = 'listings/listings_single';
#------

#About
$route['(?i)login'] = 'sign_in';
$route['(?i)logout'] = 'sign_in/sign_out';
#------

#About
$route['(?i)about'] = 'about';
$route['(?i)contact'] = 'about/contact';
#------

#send_request
$route['(?i)send_request'] = 'send_request';
#------

#About
$route['(?i)admin'] = 'admin';
$route['(?i)admin/draft'] = 'admin/draft';
$route['(?i)add_property'] = 'admin/add_property';
$route['(?i)edit_property/(:num)'] = 'admin/edit_property';
$route['(?i)admin/add_property/form'] = 'admin/add_property_form';
$route['(?i)admin/edit_property/form'] = 'admin/edit_property_form';
$route['(?i)admin/edit_property/form/delete'] = 'admin/delete_property_form';
#------

#defaults
$route['(?i)upload/pic/attch'] = 'general_actions/general_pic_attach';
$route['default_controller'] = 'home';
$route['404_override'] = 'errors/page_missing';
$route['translate_uri_dashes'] = TRUE;



