<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 * Documentation:
 * Nonce and user_id taken from the REQUEST so that it gets the value when the user is not logged in and then logs in and refreshes the block.
 * Otherwise, these 2 values are never printed, since checkpoint will stop the execution
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EditMembership {

	function execute(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);
		if ($errors) {
			return;
		}

		return $this->update($errors, $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;		

		$community = is_user_logged_in() ? get_current_user_id() : '';
		$form_data = array(
			'community' => $community,
			'user_id' => $_REQUEST['uid'],
			'nonce' => $_REQUEST['_wpnonce'],
			'status' => trim($gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS, $atts)),
			'privileges' => $gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES, $atts),
			'tags' => $gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS, $atts),
		);

		return $form_data;
	}	

	protected function validate(&$errors, $form_data) {
		
		$user_id = $form_data['user_id'];		
		if (!$user_id) {
			$errors[] = __('The user is missing', 'ure-popprocessors');
			return;
		}

		$nonce = $form_data['nonce'];		
		if (!gd_verify_nonce( $nonce, GD_NONCE_EDITMEMBERSHIPURL, $user_id)) {
			$errors[] = __('Incorrect URL', 'ure-popprocessors');
			return;
		}

		$status = $form_data['status'];		
		if (!$status) {
			$errors[] = __('The status has not been set', 'ure-popprocessors');
		}
	}

	protected function update(&$errors, $form_data) {

		$user_id = $form_data['user_id'];		
		$community = $form_data['community'];		
		$new_community_status = $form_data['status'];		
		$new_community_privileges = $form_data['privileges'];		
		$new_community_tags = $form_data['tags'];

		// Get all the current values for that user
		$status = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
		$privileges = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);
		$tags = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

		// Update these values with the changes for this one community
		// The community will already be there, since it was added when the user updated My Communities.
		// And even if the user selected no privileges or tags, then GD_METAVALUE_NONE will be set, so the db metavalue entry should always exist
		
		// Remove existing ones
		$current_community_status = gd_ure_find_community_metavalues($community, $status, false);
		$current_community_privileges = gd_ure_find_community_metavalues($community, $privileges, false);
		$current_community_tags = gd_ure_find_community_metavalues($community, $tags, false);

		$status = array_diff(
			$status, 
			$current_community_status
		);
		$privileges = array_diff(
			$privileges, 
			$current_community_privileges
		);
		$tags = array_diff(
			$tags, 
			$current_community_tags
		);

		// Add the new ones
		$status[] = gd_ure_get_community_metavalue_currentcommunity($new_community_status);
		if (!empty($new_community_privileges)) {
			$privileges = array_merge(
				$privileges, 
				array_map('gd_ure_get_community_metavalue_currentcommunity', $new_community_privileges)
			);
		}
		else {
			$privileges[] = gd_ure_get_community_metavalue_currentcommunity(GD_METAVALUE_NONE);
		}
		if (!empty($new_community_tags)) {
			$tags = array_merge(
				$tags, 
				array_map('gd_ure_get_community_metavalue_currentcommunity', $new_community_tags)
			);
		}
		else {
			$tags[] = gd_ure_get_community_metavalue_currentcommunity(GD_METAVALUE_NONE);
		}

		// Update in the DB
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, $status);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, $privileges);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS, $tags);

		// Allow ACF to also save the value in the DB
		do_action('GD_EditMembership:update', $user_id, $community, $new_community_status, $new_community_privileges, $new_community_tags);

		return true;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_editmembership;
$gd_editmembership = new GD_EditMembership();