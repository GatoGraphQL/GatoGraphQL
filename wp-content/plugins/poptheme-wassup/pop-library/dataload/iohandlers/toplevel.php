<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_DATALOAD_IOHANDLER_TOPLEVEL', 'custom-toplevel');

class GD_Custom_DataLoad_IOHandler_TopLevel extends GD_DataLoad_IOHandler_TopLevel {

    function get_name() {
    
		return GD_CUSTOM_DATALOAD_IOHANDLER_TOPLEVEL;
	}

	function get_vars($atts, $iohandler_atts) {

		$ret = parent::get_vars($atts, $iohandler_atts);

		$vars = GD_TemplateManager_Utils::get_vars();
		
		// Silent document? (Opposite to Update the browser URL and Title?)
		if ($vars['fetching-json-quickview'] || $vars['fetching-json-navigator']) {

			// Always silent for the quickView or the Navigator
			$ret[GD_URLPARAM_SILENTDOCUMENT] = true;
		}

		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_IOHandler_TopLevel();