<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_Job {

    function __construct() {
    
		global $pop_cdncore_job_manager;
		$pop_cdncore_job_manager->add($this);
	}
    
    public function get_js_path() {
        
        return '';
    }

    public function get_configuration() {
        
        return array();
    }
}

