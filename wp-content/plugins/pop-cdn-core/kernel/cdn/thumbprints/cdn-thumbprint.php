<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_ThumbprintBase {

    function __construct() {
    
		global $pop_cdncore_thumbprint_manager;
		$pop_cdncore_thumbprint_manager->add($this);
	}

    public function get_name() {
        
        return '';
    }
    
    public function get_query() {
        
        return array();
    }

    public function execute_query($query) {
        
        return '';
    }
    
    public function get_timestamp($object_id) {

        return (int) 0;
    }
}

