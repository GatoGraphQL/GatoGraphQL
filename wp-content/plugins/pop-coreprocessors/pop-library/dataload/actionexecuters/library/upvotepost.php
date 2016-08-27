<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UpvotePost extends GD_UpvoteUndoUpvotePost {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$user_id = get_current_user_id();
			$target_id = $form_data['target_id'];

			// Check that the logged in user has not already recommended this post
			$value = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_UPVOTESPOSTS);
			if (in_array($target_id, $value)) {
				
				$errors[] = sprintf(
					__('You have already up-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
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
		do_action('gd_upvotepost', $target_id, $form_data);
	}

	protected function update($form_data) {

		$user_id = get_current_user_id();
		$target_id = $form_data['target_id'];

		// Update value
		GD_MetaManager::add_user_meta($user_id, GD_METAKEY_PROFILE_UPVOTESPOSTS, $target_id);
		GD_MetaManager::add_post_meta($target_id, GD_METAKEY_POST_UPVOTEDBY, $user_id);

		// Update the counter
		$count = GD_MetaManager::get_post_meta($target_id, GD_METAKEY_POST_UPVOTECOUNT, true);
		GD_MetaManager::update_post_meta($target_id, GD_METAKEY_POST_UPVOTECOUNT, ($count + 1), true);

		// Had the user already executed the opposite (Up-vote => Down-vote, etc), then undo it
		$opposite = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_DOWNVOTESPOSTS);
		if (in_array($target_id, $opposite)) {
			
			global $gd_undodownvotepost;
			$gd_undodownvotepost->undo($form_data);
		}

		return parent::update($form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_upvotepost;
$gd_upvotepost = new GD_UpvotePost();