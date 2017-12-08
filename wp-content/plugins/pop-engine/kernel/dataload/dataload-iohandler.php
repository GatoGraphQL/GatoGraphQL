<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_VARS', 'vars');
define ('GD_DATALOAD_PARAMS', 'params');
define ('GD_DATALOAD_VISIBLEPARAMS', 'visibleparams');
define ('GD_DATALOAD_PUSHURLATTS', 'pushurlatts');
// define ('GD_DATALOAD_INTERNALPARAMS', 'internalparams');
define ('GD_DATALOAD_LAZY', 'lazy');
// define ('GD_DATALOAD_NOCONTENTLOADED', 'no-content-loaded');
define ('GD_DATALOAD_CONTENTLOADED', 'content-loaded');
define ('GD_DATALOAD_VALIDATECONTENTLOADED', 'validate-content-loaded');
define ('GD_DATALOAD_LOAD', 'load');
define ('GD_DATALOAD_INTERCEPTURLS', 'intercept-urls');
define ('GD_DATALOAD_EXTRAINTERCEPTURLS', 'extra-intercept-urls');
define ('GD_DATALOAD_REPLICATEBLOCKSETTINGSIDS', 'replicate-blocksettingsids');

add_filter('gd_jquery_constants', 'gd_jquery_constants_iohandler');
function gd_jquery_constants_iohandler($jquery_constants) {

	$jquery_constants['DATALOAD_VARS'] = GD_DATALOAD_VARS;	
	$jquery_constants['DATALOAD_PARAMS'] = GD_DATALOAD_PARAMS;	
	$jquery_constants['DATALOAD_VISIBLEPARAMS'] = GD_DATALOAD_VISIBLEPARAMS;	
	$jquery_constants['DATALOAD_PUSHURLATTS'] = GD_DATALOAD_PUSHURLATTS;	
	// $jquery_constants['DATALOAD_INTERNALPARAMS'] = GD_DATALOAD_INTERNALPARAMS;	
	$jquery_constants['DATALOAD_CONTENTLOADED'] = GD_DATALOAD_CONTENTLOADED;	
	return $jquery_constants;
}

class GD_DataLoad_IOHandler {

    function __construct() {
    
		global $gd_dataload_iohandle_manager;
		$gd_dataload_iohandle_manager->add($this->get_name(), $this);
	}

    /**
     * Function to override
     */
    function get_name() {
    
		return null;
	}

	function get_vars($atts, $iohandler_atts) {

		// Important: initially the vars are the $atts! Never override this line!
		// (So then all get_vars child function must always call parent::get_vars)
		return $atts;
	}

	function get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {

		$ret = array();

		// If we got param 'origin', send it back. It is used to customize the results based on who is the origin.
		// Eg: TPP Debate Create OpinionatedVoted in homepage/single post, they have different behaviours but only 1 action/iohandler to handle both
		if ($origin = $vars_atts['origin']) {
			$ret[GD_DATALOAD_PARAMS]['origin'] = $origin;
		}
	
		return $ret;
	}
	function get_domain_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {

		return array();
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed = null, $atts) {
	
		$ret = array();

		if ($intercept_urls = $iohandler_atts[GD_DATALOAD_INTERCEPTURLS]) {
			
			$ret[GD_DATALOAD_INTERCEPTURLS] = $intercept_urls;
			
			if ($extra_intercept_urls = $iohandler_atts[GD_DATALOAD_EXTRAINTERCEPTURLS]) {
				$ret[GD_DATALOAD_EXTRAINTERCEPTURLS] = $extra_intercept_urls;
			}
		}
		if ($replicate_blocksettingsids = $iohandler_atts[GD_DATALOAD_REPLICATEBLOCKSETTINGSIDS]) {
			
			$ret[GD_DATALOAD_REPLICATEBLOCKSETTINGSIDS] = $replicate_blocksettingsids;
		}

		return $ret;
	}

	function get_backgroundurls($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		return array();
	}
}
