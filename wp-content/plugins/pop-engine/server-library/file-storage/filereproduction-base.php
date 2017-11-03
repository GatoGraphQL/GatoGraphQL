<?php 
class PoP_Engine_FileReproductionBase {

    function __construct() {
    
		if ($renderer = $this->get_renderer()) {
    		$renderer->add($this);
        }
	}
    
    public function get_renderer() {
        
        return null;
    }
    
    public function get_js_path() {
        
        return '';
    }

    public function get_configuration() {
        
        return array();
    }

    public function get_jsonencode_options() {
        
        // Documentation: https://secure.php.net/manual/en/function.json-encode.php
        return 0;
    }
}

