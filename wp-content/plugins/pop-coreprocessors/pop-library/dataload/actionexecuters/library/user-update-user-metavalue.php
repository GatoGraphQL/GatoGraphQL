<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UpdateUserMetaValue_User extends GD_UpdateUserMetaValue {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$target_id = $form_data['target_id'];
			
			// Make sure the user exists
			$target = get_user_by('id', $target_id);
			if (!$target) {

				$errors[] = __('The requested user does not exist.', 'pop-coreprocessors');
			}
		}
	}

	protected function get_request_key() {

		return 'uid';
	}

	protected function additionals($target_id, $form_data) {

		do_action('gd_updateusermetavalue:user', $target_id, $form_data);
		parent::additionals($target_id, $form_data);
	}
}