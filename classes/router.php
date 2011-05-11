<?php
class HCP_Router {
	
	/*
	*		More specific routes belong on top and will be matched first
	*/
	var $routes = array(
		'market-place/:id/edit/fish' => 'application#fish',
		'market-place/:id/edit' => 'application#edit',
		'market-place/post' => 'application#new',
		'market-place/:id' => 'application#view',
		'market-place' => 'application#index'
	);
	
	var $match = false;
	var $dynamic_segments = array();
	var $request_segments = array();
	var $route_segments = array();
	var $wp;
	var $wp_query;
	var $debug = false;
	
	function __construct() {
		global $wp;
		global $wp_query;
		$this->wp = &$wp;
		$this->wp_query = &$wp_query;
	}
	
	function process_request() {
		if(!empty($this->wp->request)) {
			// Split the request into segments
			$request_segments = explode('/', $this->wp->request);
			$request_segment_count = count($request_segments);
			// Loop through each route and try to find a match
			foreach($this->routes as $route => $controller_action) {
				// Break our route into segments and see if a match is possible
				$route_segments = explode('/', $route);
				$route_segments_count = count($route_segments);
				if($request_segment_count < $route_segments_count) {
					// Our route requires more segments than the request has. Skip
					// to the next route
					continue;
				}
				// Loop through each route segment
				foreach($route_segments as $index => $segment) {
					if($this->debug) {
						echo $route.': '.$index.' of '.($route_segments_count - 1).'<br />';
					}
					// If we already have a match do not continue this loop
					if($this->match) {
						break;
					}
					if($request_segment_count)
					// Detect dynamic segments in the route and add them to
					// our dynamic_segments class variable. This will be used
					// when we pass this route to the Wordpress query_vars.
					// Dynamic segments can be match against anything, while
					// non-dynamic segments (with a : in the route) need to be
					// an exact match.
					if(strpos($segment, ':') === 0) {
						$normalized_name = str_replace(':', '', $segment);
						$this->dynamic_segments[$normalized_name] = $request_segments[$index];
					}elseif($segment != $request_segments[$index]) {
						break;
					}
		
					// If we have reached the end of this route and everything has matched
					// then we should consider this a final match.
					if($index === ($route_segments_count - 1)) {
						$this->match = $controller_action;
					}
				}
			}
			// If we have a match then we should continue with our plugin
			if($this->match) {
				$controller_action = explode('#', $this->match);
				$this->controller = $controller_action[0];
				$this->action = $controller_action[1];
				$this->hcp_parse_request();
			}
		}
	}
	
	/*
	*		Sets the proper wp_query variables
	*/
	function hcp_parse_request() {
		$hcp_query_vars = array(
			'controller' => $this->controller,
			'action' => $this->action,
		);
		$hcp_query_vars = array_merge($hcp_query_vars, $this->dynamic_segments);
		$this->wp_query->query_vars['hcp_query'] = $hcp_query_vars;
	}
	
}
?>