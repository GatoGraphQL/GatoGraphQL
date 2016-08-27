<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_NotificationMarkAsRead extends GD_NotificationMarkAsReadUnread {

	protected function get_status() {

		return AAL_POP_STATUS_READ;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_notification_markasread;
$gd_notification_markasread = new GD_NotificationMarkAsRead();