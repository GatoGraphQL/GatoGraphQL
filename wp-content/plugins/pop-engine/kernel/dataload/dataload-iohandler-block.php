<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_BlockIOHandler extends GD_DataLoad_IOHandler {

	/**
	 * Addition of this function at Block Level
	 */
	function get_crawlable_data($feedback, $params, $data_settings) {

		return array();
	}

	function get_vars($atts, $iohandler_atts) {

		$ret = parent::get_vars($atts, $iohandler_atts);

		// Timestamp: return whatever was given (or empty string if nothing given)
		// $ret[GD_URLPARAM_TIMESTAMP] = $atts[GD_URLPARAM_TIMESTAMP] ? $atts[GD_URLPARAM_TIMESTAMP] : '';

		// If there's a filter, also add it
		if ($filter = $atts[GD_DEFINITIONS_FILTEROBJECT]) {

			$ret[GD_DEFINITIONS_FILTEROBJECT] = $filter;
		}

		return $ret;
	}

	function get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);
		
		// Always include this param for any block (it can be overriden in implementations, like block-list)
		// $ret[GD_DATALOAD_INTERNALPARAMS][GD_URLPARAM_STOPFETCHING] = false;
		$ret[GD_URLPARAM_STOPFETCHING] = false;

		// Content Loaded?
		// $ret[GD_DATALOAD_INTERNALPARAMS][GD_DATALOAD_CONTENTLOADED] = $iohandler_atts[GD_DATALOAD_CONTENTLOADED];
		$ret[GD_DATALOAD_CONTENTLOADED] = $iohandler_atts[GD_DATALOAD_CONTENTLOADED];

		return $ret;
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);
		
		$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_PAGESECTION_SETTINGSID] = $atts['pagesection-settings-id'];
		// For pageSections other than the main, send this information
		// $pagesection_settings_id = $atts['pagesection-settings-id'];
		// if ($pagesection_settings_id != GD_TEMPLATEID_PAGESECTIONSETTINGSID_MAIN) {
			
		// $ret[GD_DATALOAD_PARAMS][GD_URLPARAM_PAGESECTION_SETTINGSID] = $pagesection_settings_id;
		// }

		// Hide Block?
		// $ret[GD_URLPARAM_HIDEBLOCK] = false;
		$ret[GD_URLPARAM_HIDEBLOCK] = empty($dataset) && $iohandler_atts[GD_URLPARAM_HIDDENIFEMPTY];

		// validate-checkpoints
		if ($iohandler_atts['validate-checkpoints']) {

			$ret[GD_URLPARAM_VALIDATECHECKPOINTS] = true;

			if ($iohandler_atts['checkpointvalidation-failed']) {
				
				$ret['checkpointvalidation-failed'] = true;
			}
		}

		// Title
		if ($title = $iohandler_atts['title']) {

			$ret[GD_URLPARAM_TITLE] = $title;
			if ($title_link = $iohandler_atts['title-link']) {

				$ret[GD_URLPARAM_TITLELINK] = $title_link;
			}
		}

		return $ret;
	}
}
	
