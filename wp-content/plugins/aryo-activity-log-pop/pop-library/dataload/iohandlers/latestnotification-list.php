<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_LATESTNOTIFICATIONLIST', 'latestnotification-list');

class GD_DataLoad_IOHandler_LatestNotificationList extends GD_DataLoad_IOHandler_NotificationList {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_LATESTNOTIFICATIONLIST;
	}

	function get_hist_time($atts, $iohandler_atts) {

		if (is_user_logged_in()) {

			// Since the last time the user logged in
			$lastaccess = GD_MetaManager::get_user_meta(get_current_user_id(), POP_METAKEY_USER_LASTACCESS, true);
			
			// Alert the user about all past notifications
			// Newly created user accounts, there's still no lastaccess, so just give a "1" to make the comparison, it will return all results
			if (!$lastaccess) {
				return 1;
			}
			return $lastaccess;
		}
    
		// User not logged in => return now
		// return POP_CONSTANT_CURRENTTIMESTAMP;//current_time('timestamp');
		return parent::get_hist_time($atts, $iohandler_atts);
	}

	function get_hist_time_compare($atts, $iohandler_atts) {
    
		return '>';
	}

	function get_vars($atts, $iohandler_atts) {
    
		$ret = parent::get_vars($atts, $iohandler_atts);

		// Bring all results
		$ret['paged'] = 1;
		$ret['limit'] = -1;

		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_LatestNotificationList();
