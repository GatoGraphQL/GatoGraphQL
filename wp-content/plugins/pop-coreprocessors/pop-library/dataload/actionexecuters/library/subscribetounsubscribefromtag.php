<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_SubscribeToUnsubscribeFromTag extends GD_UpdateUserMetaValue {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$target_id = $form_data['target_id'];
			
			// Make sure the post exists
			$target = get_tag($target_id);
			if (!$target) {

				$errors[] = __('The requested topic/tag does not exist.', 'pop-coreprocessors');
			}
		}
	}

	protected function get_request_key() {

		return 'tid';
	}

	protected function additionals($target_id, $form_data) {

		do_action('gd_subscritetounsubscribefrom_tag', $target_id, $form_data);
		parent::additionals($target_id, $form_data);
	}
}