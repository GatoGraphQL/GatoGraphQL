<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FollowUser extends GD_FollowUnfollowUser {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$user_id = get_current_user_id();
			$target_id = $form_data['target_id'];

			if ($user_id == $target_id) {

				$errors[] = __('You can\'t follow yourself!', 'pop-coreprocessors');
			}
			else {

				// Check that the logged in user does not currently follow that user
				$value = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_FOLLOWSUSERS);
				if (in_array($target_id, $value)) {
					
					$errors[] = sprintf(
						__('You are already following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
						get_the_author_meta('display_name', $target_id)
					);
				}
			}
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($target_id, $form_data) {

		parent::additionals($target_id, $form_data);
		do_action('gd_followuser', $target_id, $form_data);
	}

	// protected function update_value($value, $form_data) {

	// 	// Add the user to follow to the list
	// 	$target_id = $form_data['target_id'];
	// 	$value[] = $target_id;
	// }

	protected function update($form_data) {

		$user_id = get_current_user_id();
		$target_id = $form_data['target_id'];

		// Comment Leo 02/10/2015: added redundant values, so that we can query for both "Who are my followers" and "Who I am following"
		// and make both searchable and with pagination
		// Update values
		GD_MetaManager::add_user_meta($user_id, GD_METAKEY_PROFILE_FOLLOWSUSERS, $target_id);
		GD_MetaManager::add_user_meta($target_id, GD_METAKEY_PROFILE_FOLLOWEDBY, $user_id);

		// Update the counter
		$count = GD_MetaManager::get_user_meta($target_id, GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
		GD_MetaManager::update_user_meta($target_id, GD_METAKEY_PROFILE_FOLLOWERSCOUNT, ($count + 1), true);

		return parent::update($form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_followuser;
$gd_followuser = new GD_FollowUser();