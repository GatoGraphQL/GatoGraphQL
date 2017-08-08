<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_LIST_NOLIMIT_EVENT', 'list-nolimit-event');

class GD_DataLoad_IOHandler_ListNoLimit_Event extends GD_DataLoad_IOHandler_List {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_LIST_NOLIMIT_EVENT;
	}

    function get_vars($atts, $iohandler_atts) {
    
		$ret = parent::get_vars($atts, $iohandler_atts);

		// Always bring all results
		$ret[GD_URLPARAM_LIMIT] = 0;	

		return $ret;
	}
	
	
	// function get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	function get_domain_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_domain_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);
		
		// Send back the year / month
		// Delete 'paged' (Since 'limit' => 0, no need anymore)
		unset($ret[GD_DATALOAD_PARAMS][GD_URLPARAM_PAGED]);

		return $ret;
	}
	

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_ListNoLimit_Event();
