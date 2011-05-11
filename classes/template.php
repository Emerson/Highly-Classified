<?php
class HCP_Template {
    
    var $view = '';
    var $data = array();
    
    function render($view) {
        $template_path = dirname(dirname(__FILE__)).'/views/'.$view.'.php';
        ob_start();
        include($template_path);
        $output = ob_get_clean();
        return $output;
    }
    
}
?>