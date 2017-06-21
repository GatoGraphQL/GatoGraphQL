<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DownvotePost extends GD_DownvoteUndoDownvotePost {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$vars = GD_TemplateManager_Utils::get_vars();
			$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
			$target_id = $form_data['target_id'];

			// Check that the logged in user has not already recommended this post
			$value = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_DOWNVOTESPOSTS);
			if (in_array($target_id, $value)) {
				
				$errors[] = sprintf(
					__('You have already down-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
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
		do_action('gd_downvotepost', $target_id, $form_data);
	}

	protected function update($form_data) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
		$target_id = $form_data['target_id'];

		// Update value
		GD_MetaManager::add_user_meta($user_id, GD_METAKEY_PROFILE_DOWNVOTESPOSTS, $target_id);
		GD_MetaManager::add_post_meta($target_id, GD_METAKEY_POST_DOWNVOTEDBY, $user_id);

		// Update the counter
		$count = GD_MetaManager::get_post_meta($target_id, GD_METAKEY_POST_DOWNVOTECOUNT, true);
		GD_MetaManager::update_post_meta($target_id, GD_METAKEY_POST_DOWNVOTECOUNT, ($count + 1), true);

		// Had the user already executed the opposite (Up-vote => Down-vote, etc), then undo it
		$opposite = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_UPVOTESPOSTS);
		if (in_array($target_id, $opposite)) {
			
			global $gd_undoupvotepost;
			$gd_undoupvotepost->undo($form_data);
		}

		return parent::update($form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_downvotepost;
$gd_downvotepost = new GD_DownvotePost();