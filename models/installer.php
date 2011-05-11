<?php
class HCP_Installer {
    
		var $version = '0.1';
		
		var $tables = array(
			
			'hcp_users' 			=> 		"CREATE TABLE `{table_name}` (
							  							`id` int(11) NOT NULL DEFAULT '0',
															`created` datetime NOT NULL,
														  `username` varchar(255) NOT NULL DEFAULT '',
														  `email` varchar(255) NOT NULL DEFAULT '',
														  `password` varchar(255) NOT NULL DEFAULT '',
														  PRIMARY KEY (`id`)
															) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
			
			'hcp_classifieds' => 	  "CREATE TABLE `{table_name}` (
														  `id` int(11) NOT NULL DEFAULT '0',
														  `user_id` int(11) DEFAULT NULL,
														  `category_id` int(11) DEFAULT NULL,
														  `title` varchar(255) NOT NULL DEFAULT '',
														  `description` text NOT NULL,
														  `created` datetime DEFAULT NULL,
														  `modified` datetime DEFAULT NULL,
														  PRIMARY KEY (`id`)
														  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
														
			'hcp_categories'  =>    "CREATE TABLE `{table_name}` (
													    `id` int(11) NOT NULL DEFAULT '0',
															`title` varchar(255) NOT NULL DEFAULT '',
															`slug` varchar(255) NOT NULL DEFAULT '',
															`fields_id` int(11) DEFAULT NULL,
															PRIMARY KEY (`id`)
															) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
		);

		function __construct() {
			
		}	

    function install() {
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				global $wpdb;
        foreach($this->tables as $name => $create_syntax) {
					$table_name = $wpdb->prefix . $name;
					$query = preg_replace('/{table_name}/', $table_name, $create_syntax);
					dbDelta($query);
				}
				add_option('hcp_version', $this->version);
    }
    
    function upgrade() {
        if(get_site_option('hcp_version') != $this->version) {
					$this->install();
				}
    }

    function uninstall() {
        
    }
    
}
?>