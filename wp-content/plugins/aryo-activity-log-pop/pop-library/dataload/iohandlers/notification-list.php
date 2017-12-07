<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_NOTIFICATIONLIST', 'notification-list');

class GD_DataLoad_IOHandler_NotificationList extends GD_DataLoad_IOHandler_List {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_NOTIFICATIONLIST;
	}

	function get_hist_time($atts, $iohandler_atts) {
    
		if (GD_TemplateManager_Utils::loading_latest()) {

			return $atts[GD_URLPARAM_TIMESTAMP];
		}

		// hist_time: needed so we always query the notifications which happened before hist_time,
		// so that if there were new notification they don't get on the way, and fetching more will not bring again a same record
		if (isset($atts['hist_time'])) {

			return $atts['hist_time'];
		}

		// Baseline: return now
		return POP_CONSTANT_CURRENTTIMESTAMP;//current_time('timestamp');
	}

	function get_hist_time_compare($atts, $iohandler_atts) {
    
		if (GD_TemplateManager_Utils::loading_latest()) {

			return '>';
		}

		if (isset($atts['hist_time_compare'])) {

			return $atts['hist_time_compare'];
		}

		return '<=';
	}

	function get_vars($atts, $iohandler_atts) {
    
		$ret = parent::get_vars($atts, $iohandler_atts);

		$ret['hist_time'] = $this->get_hist_time($atts, $iohandler_atts);
		$ret['hist_time_compare'] = $this->get_hist_time_compare($atts, $iohandler_atts);

		// // Erase the timestamp when first loading the frame, so that the first time it executes loadLatest,
		// // it will send no timestamp, and it will then use the current_time
		// if (GD_TemplateManager_Utils::loading_frame()) {
		// 	unset($ret[GD_URLPARAM_TIMESTAMP]);
		// }

		return $ret;
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);

		// Send the hist_time back only for dynamic pages, so the time does not get cached
		// It will always work fine, since /notifications is stateful, so the first time it is invoked it will get that current time and set it
		if (GD_TemplateManager_Utils::page_requires_user_state()) {
			$ret[GD_DATALOAD_PARAMS]['hist_time'] = $vars['hist_time'];
		}

		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_NotificationList();
