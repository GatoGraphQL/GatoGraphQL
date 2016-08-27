<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AAL_PoPProcessors_NotificationUtils {

	public static function get_notificationcount_id() {

		return apply_filters('AAL_PoPProcessors_NotificationUtils:notificationcount_id', 'notifications-count');
	}
}