<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_ServiceWorkers_Job {

    function __construct() {
    
		global $pop_serviceworkers_job_manager;
		$pop_serviceworkers_job_manager->add($this->get_scope(), $this);
	}
    
    /**
     * Function to override
     */
    function get_scope() {

		return site_url('/', 'relative');
	}

    // function get_sw_js_filename() {

    //     return '';
    // }
    
    // public function get_sw_js_dir() {
        
    //     return '';
    // }
    
    public function get_sw_js_path() {
        
        // return $this->get_sw_js_dir().'/'.$this->get_sw_js_filename();
        return '';
    }
    
    public function get_dependencies() {
        
        return array();
    }

    public function get_sw_codereplacements() {
        
        global $pop_serviceworkers_manager;
        return array(
            '$dependenciesFolder' => $pop_serviceworkers_manager->get_dependencies_foldername(),
        );
    }

    public function get_sw_configuration() {
        
        // // Add a string before the version, since starting with a number could make mess
        // return array(
        //     '$version' => 'PoP:'.pop_version(),
        // );
        return array();
    }
}

