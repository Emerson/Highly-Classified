<?php
/*
Plugin Name: Highly Classified
Plugin URI: http://www.emersonlackey.com/highly-classified/
Description: A simple and clean Classified ad plugin written for Wordpress.
Version: 0.1
Author: Emerson Lackey
Author URI: http://www.emersonlackey.com
License: GPL2
*/

$hc_plugin_path = dirname(__FILE__).'/';
require($hc_plugin_path.'classes/installer.php');
require($hc_plugin_path.'classes/template.php');

// Load our admin files if needed
if(is_admin()) {
    require($hc_plugin_path.'classes/admin.php');
}

// Load the front end of the applicaion otherwise
if(!is_admin()) {
    require($hc_plugin_path.'classes/application.php');
}

?>