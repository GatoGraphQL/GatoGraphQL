<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_SubscribeToTag extends GD_SubscribeToUnsubscribeFromTag {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$vars = GD_TemplateManager_Utils::get_vars();
			$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
			$target_id = $form_data['target_id'];

			// Check that the logged in user has not already subscribed to this tag
			$value = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);
			if (in_array($target_id, $value)) {
				
				$tag = get_tag($target_id);
				$errors[] = sprintf(
					__('You have already subscribed to <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
					PoP_TagUtils::get_tag_symbol().$tag->name
				);
			}
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($target_id, $form_data) {

		parent::additionals($target_id, $form_data);
		do_action('gd_subscribetotag', $target_id, $form_data);
	}

	protected function update($form_data) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
		$target_id = $form_data['target_id'];

		// Update value
		GD_MetaManager::add_user_meta($user_id, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS, $target_id);
		GD_MetaManager::add_term_meta($target_id, GD_METAKEY_TERM_SUBSCRIBEDBY, $user_id);

		// Update the counter
		$count = GD_MetaManager::get_term_meta($target_id, GD_METAKEY_TERM_SUBSCRIBERSCOUNT, true);
		GD_MetaManager::update_term_meta($target_id, GD_METAKEY_TERM_SUBSCRIBERSCOUNT, ($count + 1), true);

		return parent::update($form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_subscribetotag;
$gd_subscribetotag = new GD_SubscribeToTag();