<?php
/*
Plugin Name: Highly Classified
Plugin URI: http://www.emersonlackey.com/highly-classified/
Description: A simple and clean Classified ad plugin written for Wordpress.
Version: 0.1
Author: Emerson Lackey
Author URI: http://www.emersonlackey.com
License: GPL2

Copyright 2011  Emerson Lackey  (email : emerson.lackey@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

$hc_plugin_path = dirname(__FILE__).'/';
require($hc_plugin_path.'models/installer.php');
require($hc_plugin_path.'classes/router.php');
require($hc_plugin_path.'classes/template.php');

class HCP_Plugin {

	public $controller;
	public $action;
	public $hcp_plugin_path;
	
	function __construct() {
		$this->controller = false;
		$this->action = false;
		$hcp_plugin_path = dirname(__FILE__).'/';
	}
	
	function hcp_install() {
		$installer = new HCP_Installer();
		$installer->install();
	}
	
	function hcp_version_check() {
		$installer = new HCP_Installer();
		$installer->upgrade();
	}
	
	
	/*
	*		This should set the action and controller if a proper route is found
	*		and then hand things off to proper controller and action.
	*/
	function hcp_router() {
		$router = new HCP_Router();
		$router->process_request();
		// If our routes give a valid controller and action then
		// we can instantiate our class and call the requested action
		if($router->controller && $router->action) {
			$this->controller = $router->controller;
			$this->action = $router->action;
			require_once(dirname(__FILE__).'/controllers/'.$this->controller.'.php');
			$controller_name = "HCP_".ucfirst($this->controller);
			$action_name = $this->action;
			$controller = new $controller_name;
			$controller->$action_name();
		}
	}
	
	function hcp_template() {
		return dirname(__FILE__).'/views/'.$this->controller.'/'.$this->action.'.php';
	}
	
	
}

$HCP_Plugin = new HCP_Plugin();


// Plugin Activation
register_activation_hook(__FILE__, 'hcp_install');

// Plugin Version Check
add_action('plugins_loaded', array(&$HCP_Plugin, 'hcp_version_check'));

// Plugin Router
// add_action('parse_request', array(&$HCP_Plugin, 'hcp_router')); // too early
add_action('pre_get_posts', array(&$HCP_Plugin, 'hcp_router'));

add_filter( '404_template', array(&$HCP_Plugin, 'hcp_template'));

// Load our admin files if needed
// if(is_admin()) {
//     require($hc_plugin_path.'controllers/admin.php');
// }
// 
// // Load the front end of the applicaion otherwise
// if(!is_admin()) {
//     require($hc_plugin_path.'controllers/application.php');
// }

?>