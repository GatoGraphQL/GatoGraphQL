<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_AutomatedEmails_Manager {

    var $automatedemails;
    
    function __construct() {
    
		$this->automatedemails = array();
	}
	
    function add($automatedemail) {
    
		// Each page can have many automatedemails (eg: 1 for users, 1 for recipients from Gravity Forms)
    	if (!$this->automatedemails[$automatedemail->get_page()]) {
    		$this->automatedemails[$automatedemail->get_page()] = array();
    	}
		$this->automatedemails[$automatedemail->get_page()][] = $automatedemail;		
	}
	
	function get_automatedemails($page) {

		return $this->automatedemails[$page];
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_automatedemails_manager;
$pop_automatedemails_manager = new PoP_AutomatedEmails_Manager();
