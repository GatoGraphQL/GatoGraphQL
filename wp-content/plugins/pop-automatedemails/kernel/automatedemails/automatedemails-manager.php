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
    
		$this->automatedemails[$automatedemail->get_page()] = $automatedemail;		
	}
	
	function get_automatedemail($page) {

		return $this->automatedemails[$page];
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_automatedemails_manager;
$pop_automatedemails_manager = new PoP_AutomatedEmails_Manager();
