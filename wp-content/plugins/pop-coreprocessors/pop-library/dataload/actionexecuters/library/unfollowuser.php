<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UnfollowUser extends GD_FollowUnfollowUser {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$user_id = get_current_user_id();
			$target_id = $form_data['target_id'];

			// Check that the logged in user does currently follow that user
			$value = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_FOLLOWSUSERS);
			if (!in_array($target_id, $value)) {
				
				$errors[] = sprintf(
					__('You were not following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
					get_the_author_meta('display_name', $target_id)
				);
			}
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($target_id, $form_data) {

		parent::additionals($target_id, $form_data);
		do_action('gd_unfollowuser', $target_id, $form_data);
	}

	// protected function update_value($value, $form_data) {

	// 	// Remove the user from the list
	// 	$target_id = $form_data['target_id'];
	// 	array_splice($value, array_search($target_id, $value), 1);
	// }
	protected function update($form_data) {

		$user_id = get_current_user_id();
		$target_id = $form_data['target_id'];

		// Update values
		GD_MetaManager::delete_user_meta($user_id, GD_METAKEY_PROFILE_FOLLOWSUSERS, $target_id);
		GD_MetaManager::delete_user_meta($target_id, GD_METAKEY_PROFILE_FOLLOWEDBY, $user_id);

		// Update the counter
		$count = GD_MetaManager::get_user_meta($target_id, GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
		GD_MetaManager::update_user_meta($target_id, GD_METAKEY_PROFILE_FOLLOWERSCOUNT, ($count - 1), true);

		return parent::update($form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_unfollowuser;
$gd_unfollowuser = new GD_UnfollowUser();