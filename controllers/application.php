<?php
class HCP_Application {
	
		var $template;
		var $wp_query;
		var $wpdb;
    
		function __construct() {
			global $wpdb;
			global $wp_query;
			$this->wpdb = $wpdb;
			$this->wp_query = $wp_query;
			$this->template = new HCP_Template();
		}

    function index() {
			$this->template->render('application/index');
		}
		
		function view() {
			$this->template->render('application/view');
		}
		
		function edit() {
			$this->template->render('application/view');
		}
    
}
?>