<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_URE_DataLoad_FieldProcessor_Users_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		return array(GD_DATALOAD_FIELDPROCESSOR_USERS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$user = $resultitem;

		switch ($field) {

			// case 'members-tab-url' :

			// 	// Summary Tab: for Communities still show only the Content from the Organization
			// 	$url = get_author_posts_url($fieldprocessor->get_id($user));
			// 	return GD_TemplateManager_Utils::add_tab($url, POP_URE_POPPROCESSORS_PAGE_MEMBERS);

			case 'edit-membership-url' :

				return gd_ure_edit_membership_url($fieldprocessor->get_id($user));

			case 'edit-memberstatus-inline-url' :

				return gd_ure_edit_membership_url($fieldprocessor->get_id($user), true);

			// case 'nonce-edit-membership-url' :
			// 	$value = gd_create_nonce(GD_NONCE_EDITMEMBERSHIPURL, $fieldprocessor->get_id($user));
			// 	break;

			case 'memberstatus':

				// All status for all communities
				$status = GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);

				// Filter status for only this community: the logged in user
				return gd_ure_community_membershipstatus_filterbycurrentcommunity($status);

			case 'memberstatus-strings' :				
				
				$selected = $fieldprocessor->get_value($user, 'memberstatus');
				$params = array(
					'selected' => $selected
				);
				$status = new GD_URE_FormInput_MultiMemberStatus($params);
				return $status->get_selected_value();

			case 'memberprivileges':

				// All privileges for all communities
				$privileges = GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);

				// Filter privileges for only this community: the logged in user
				return gd_ure_community_membershipstatus_filterbycurrentcommunity($privileges);

			case 'memberprivileges-strings' :				
				
				$selected = $fieldprocessor->get_value($user, 'memberprivileges');
				$params = array(
					'selected' => $selected
				);
				$privileges = new GD_URE_FormInput_FilterMemberPrivileges($params);
				return $privileges->get_selected_value();

			case 'membertags':

				// All privileges for all communities
				$tags = GD_MetaManager::get_user_meta($fieldprocessor->get_id($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

				// Filter privileges for only this community: the logged in user
				return gd_ure_community_membershipstatus_filterbycurrentcommunity($tags);

			case 'membertags-strings' :				
				
				$selected = $fieldprocessor->get_value($user, 'membertags');
				$params = array(
					'selected' => $selected
				);
				$tags = new GD_URE_FormInput_FilterMemberTags($params);
				return $tags->get_selected_value();

			case 'is-community' :
				return gd_ure_is_community($fieldprocessor->get_id($user)) ? true : null;

			case 'is-community-string':
				// return $fieldprocessor->get_yesno_select_string($user, 'is-community');
				return GD_DataLoad_FieldProcessor_Utils::get_boolvalue_string($fieldprocessor->get_value($resultitem, 'is-community'));

			case 'communities' :				
				// Return only the communities where the user's been accepted as a member
				return gd_ure_get_communities($fieldprocessor->get_id($user));

			case 'active-communities' :
				// Return only the communities where the user's been accepted as a member
				return gd_ure_get_communities_status_active($fieldprocessor->get_id($user));

			case 'has-active-communities' :

				$communities = $fieldprocessor->get_value($resultitem, 'active-communities');
				return !empty($communities);																												
		}

		return parent::get_value($user, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_DataLoad_FieldProcessor_Users_Hook();