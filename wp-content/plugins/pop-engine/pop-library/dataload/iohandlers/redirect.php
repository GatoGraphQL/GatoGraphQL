<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_REDIRECT', 'redirect');

class GD_DataLoad_IOHandler_Redirect extends GD_DataLoad_IOHandler_Form {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_REDIRECT;
	}

	function get_vars($atts, $iohandler_atts) {

		$ret = parent::get_vars($atts, $iohandler_atts);

		// Add the Redirect to Param. If there is none, use the referrer.
		// This is useful when coming from the Login link above the Template, which can't pass the 'redirect_to' data
		$redirect = $atts[GD_URLPARAM_REDIRECTTO] ? $atts[GD_URLPARAM_REDIRECTTO] : $_SERVER['HTTP_REFERER'];
		$ret[GD_URLPARAM_REDIRECTTO] = $redirect;

		return $ret;
	}
	
	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);
		
		// Add the Redirect to
		$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_REDIRECTTO] = $vars[GD_URLPARAM_REDIRECTTO];
		
		return $ret;
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_Redirect();
