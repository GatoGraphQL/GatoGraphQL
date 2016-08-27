<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_RecommendPost extends GD_RecommendUnrecommendPost {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$user_id = get_current_user_id();
			$target_id = $form_data['target_id'];

			// Check that the logged in user has not already recommended this post
			$value = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_RECOMMENDSPOSTS);
			if (in_array($target_id, $value)) {
				
				$errors[] = sprintf(
					__('You have already recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
					get_the_title($target_id)
				);
			}
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($target_id, $form_data) {

		parent::additionals($target_id, $form_data);
		do_action('gd_recommendpost', $target_id, $form_data);
	}

	// protected function update_value($value, $form_data) {

	// 	// Add the user to follow to the list
	// 	$target_id = $form_data['target_id'];
	// 	$value[] = $target_id;
	// }

	protected function update($form_data) {

		$user_id = get_current_user_id();
		$target_id = $form_data['target_id'];

		// Update value
		GD_MetaManager::add_user_meta($user_id, GD_METAKEY_PROFILE_RECOMMENDSPOSTS, $target_id);
		GD_MetaManager::add_post_meta($target_id, GD_METAKEY_POST_RECOMMENDEDBY, $user_id);

		// Update the counter
		$count = GD_MetaManager::get_post_meta($target_id, GD_METAKEY_POST_RECOMMENDCOUNT, true);
		GD_MetaManager::update_post_meta($target_id, GD_METAKEY_POST_RECOMMENDCOUNT, ($count + 1), true);

		return parent::update($form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_recommendpost;
$gd_recommendpost = new GD_RecommendPost();