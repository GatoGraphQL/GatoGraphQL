<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Update_MyCommunities {

	function update(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validateupdatecontent($errors, $form_data);
		if ($errors) {
			return;
		}

		// Do the Post update
		return $this->execute_update($errors, $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;		
		$user_id = is_user_logged_in() ? get_current_user_id() : '';
		$form_data = array(
			'user_id' => $user_id,
			'communities' => $gd_template_processor_manager->get_processor(GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES)->get_value(GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES, $atts),
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_createupdate_mycommunities:form_data', $form_data);
		
		return $form_data;
	}	

	protected function validateupdatecontent(&$errors, $form_data) {

		$user_id = $form_data['user_id'];

		// Validate the Organization doesn't belong to itself as a member
		if (in_array($user_id, $form_data['communities'])) {
			$errors[] = __('You are not allowed to be a Community member of yourself!', 'ure-popprocessors');
		}
	}

	protected function execute_update(&$errors, $form_data) {

		$user_id = $form_data['user_id'];

		$previous_communities = gd_ure_get_communities($user_id);
		$communities = $form_data['communities'];
		// $maybe_new_communities = array_diff($communities, $previous_communities);
		$new_communities = $banned_communities = array();

		$status = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
		
		// Check all the $maybe_new_communities and double check they are not banned
		foreach ($communities as $maybe_new_community) {

			// Empty metavalue => it's new
			$community_status = gd_ure_find_community_metavalues($maybe_new_community, $status);
			if (empty($community_status)) {

				$new_communities[] = $maybe_new_community;
			}
			else {

				// Check if this user was banned by the community
				if (in_array(GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_REJECTED, $community_status)) {
					$banned_communities[] = $maybe_new_community;
				}
			}
		}

		// Set the new communities
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

		// Set the privileges/tags for the new communities
		gd_ure_user_addnewcommunities($user_id, $new_communities);

		// If there are banned communities, show the corresponding error to the user
		if ($banned_communities) {

			$banned_communities_html = array();
			foreach ($banned_communities as $banned_community) {

				$banned_communities_html[] = sprintf(
					'<a href="%s">%s</a>',
					get_author_posts_url($banned_community),
					get_the_author_meta('display_name', $banned_community)
				);
			}
			$errors[] = sprintf(
				__('The following Organization(s) will not be active, since they claim you are not their member: %s.', 'ure-popprocessors'),
				implode(', ', $banned_communities_html)
			);
		}

		// Keep the previous values as to analyse which communities are new and send an email only to those
		$operationlog = array(
			'previous-communities' => $previous_communities,
			'new-communities' => $new_communities,
			'communities' => $communities,
		);
		
		// Allow to send an email before the update: get the current communities, so we know which ones are new
		do_action('gd_update_mycommunities:update', $user_id, $form_data, $operationlog);

		return true;
		// Update: either updated or no banned communities (even if nothing changed, tell the user update was successful)
		// return $update || empty($banned_communities);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_update_mycommunities;
$gd_update_mycommunities = new GD_Update_MyCommunities();