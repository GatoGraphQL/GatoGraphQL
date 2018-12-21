<?php 
namespace PoP\Engine\FileStorage;

abstract class FileReproductionBase {

    function __construct() {
    
		if ($renderer = $this->get_renderer()) {
    		$renderer->add($this);
        }
	}
    
    function get_renderer() {
        
        return null;
    }
    
    function get_assets_path() {
        
        return '';
    }

    function get_configuration() {
        
        return array();
    }

    function is_json_replacement() {

        return true;
    }

    function get_jsonencode_options() {
        
        // Documentation: https://secure.php.net/manual/en/function.json-encode.php
        return 0;
    }
}
