<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_NotificationMarkAllAsRead {

	protected function get_form_data($atts) {

		$form_data = array(
			'user_id' => get_current_user_id(),
		);
		
		return $form_data;
	}

	protected function additionals($form_data) {

		do_action('GD_NotificationMarkAllAsRead:additionals', $form_data);
	}

	protected function mark_all_as_read($form_data) {

		return AAL_Main::instance()->api->set_status_multiple_notifications($form_data['user_id'], AAL_POP_STATUS_READ);
	}

	function execute(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$hist_ids = $this->mark_all_as_read($form_data);
		$this->additionals($form_data);

		return $hist_ids;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_notification_markallasread;
$gd_notification_markallasread = new GD_NotificationMarkAllAsRead();