<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_UnsubscribeFromTag extends GD_SubscribeToUnsubscribeFromTag {

	protected function validate(&$errors, $form_data) {

		parent::validate($errors, $form_data);

		if (!$errors) {

			$user_id = get_current_user_id();
			$target_id = $form_data['target_id'];

			// Check that the logged in user is currently subscribed to that tag
			$value = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);
			if (!in_array($target_id, $value)) {
				
				$tag = get_tag($target_id);
				$errors[] = sprintf(
					__('You had not subscribed to <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
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
		do_action('gd_unsubscribefromtag', $target_id, $form_data);
	}

	protected function update($form_data) {

		$user_id = get_current_user_id();
		$target_id = $form_data['target_id'];

		// Update value
		GD_MetaManager::delete_user_meta($user_id, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS, $target_id);
		GD_MetaManager::delete_term_meta($target_id, GD_METAKEY_TERM_SUBSCRIBEDBY, $user_id);

		// Update the counter
		$count = GD_MetaManager::get_term_meta($target_id, GD_METAKEY_TERM_SUBSCRIBERSCOUNT, true);
		GD_MetaManager::update_term_meta($target_id, GD_METAKEY_TERM_SUBSCRIBERSCOUNT, ($count - 1), true);

		return parent::update($form_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_unsubscribefromtag;
$gd_unsubscribefromtag = new GD_UnsubscribeFromTag();