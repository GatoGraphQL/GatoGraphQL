<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_NotificationMarkAsReadUnread {

	protected function get_form_data($atts) {

		$form_data = array(
			'histid' => $_REQUEST[$this->get_request_key()],
			'user_id' => get_current_user_id(),
		);
		
		return $form_data;
	}

	protected function get_request_key() {

		return 'nid';
	}

	protected function validate(&$errors, $form_data) {

		$histid = $form_data['histid'];
		if (!$histid) {
			
			$errors[] = __('This URL is incorrect.', 'aal-pop');
		}
		else {

			// $query = array(
			// 	'histid' => $histid,
			// 	'user_id' => $form_data['user_id'],
			// 	'fields' => 'histid',
			// 	'joinstatus' => false,
			// );
			// $notification = AAL_Main::instance()->api->get_notification($query);
			$notification = AAL_Main::instance()->api->get_notification($histid);
			if (!$notification) {

				$errors[] = __('This notification does not exist.', 'aal-pop');
			}
		}
	}

	protected function additionals($histid, $form_data) {

		do_action('GD_NotificationMarkAsReadUnread:additionals', $histid, $form_data);
	}

	/**
	 * Function to override
	 */
	protected function get_status() {

		// Notice that null is also "Mark as Unread"
		return null;
	}

	protected function set_status($form_data) {

		return AAL_Main::instance()->api->set_status($form_data['histid'], $form_data['user_id'], $this->get_status());
	}

	function execute(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);
		if ($errors) {
			return;
		}

		$hist_ids = $this->set_status($form_data);
		$this->additionals($form_data['histid'], $form_data);

		return $hist_ids; //$form_data['histid'];
	}
}