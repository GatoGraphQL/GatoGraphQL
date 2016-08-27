<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UpdateUserMetaValue_Post extends GD_UpdateUserMetaValue {

	protected function eligible($post) {

		return true;
	}

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$target_id = $form_data['target_id'];
			
			// Make sure the post exists
			$target = get_post($target_id);
			if (!$target) {

				$errors[] = __('The requested post does not exist.', 'pop-coreprocessors');
			}
			else {

				// Make sure this target accepts this functionality. Eg: Not all posts can be Recommended or Up/Down-voted.
				// Discussion can be recommended only, Highlight up/down-voted only
				if (!$this->eligible($target)) {
					$errors[] = __('The requested functionality does not apply on this post.', 'pop-coreprocessors');	
				}
			}
		}
	}

	protected function get_request_key() {

		return 'pid';
	}

	protected function additionals($target_id, $form_data) {

		do_action('gd_updateusermetavalue:post', $target_id, $form_data);
		parent::additionals($target_id, $form_data);
	}
}