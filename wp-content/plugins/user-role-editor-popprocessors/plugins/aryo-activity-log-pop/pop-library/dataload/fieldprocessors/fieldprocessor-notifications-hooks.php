<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class URE_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_NOTIFICATIONS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$notification = $resultitem;

		switch ($field) {

			case 'edit-user-membership-url' :

				return gd_ure_edit_membership_url($notification->user_id);

			case 'community-members-url' :

				return GD_TemplateManager_Utils::add_tab(get_author_posts_url($notification->object_id), POP_URE_POPPROCESSORS_PAGE_MEMBERS);
				
			case 'user-url' :

				return get_author_posts_url($notification->user_id);

			// ----------------------------------------
			// All fields below were copied from plugins/user-role-editor-popprocessors/pop-library/dataload/fieldprocessors/fieldprocessor-users-hook.php,
			// where they are applied on users, while here below they are applied on notifications
			// ----------------------------------------
			case 'memberstatus':

				// object_id is the user whose membership was updated
				$status = GD_MetaManager::get_user_meta($notification->object_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);

				// Filter status for the community: user_id
				return gd_ure_community_membershipstatus_filterbycommunity($status, $notification->user_id);

			case 'memberstatus-strings' :				
				
				$selected = $fieldprocessor->get_value($notification, 'memberstatus');
				$params = array(
					'selected' => $selected
				);
				$status = new GD_URE_FormInput_MultiMemberStatus($params);
				return $status->get_selected_value();

			case 'memberprivileges':

				$privileges = GD_MetaManager::get_user_meta($notification->object_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);

				// Filter status for the community: user_id
				return gd_ure_community_membershipstatus_filterbycommunity($privileges, $notification->user_id);

			case 'memberprivileges-strings' :				
				
				$selected = $fieldprocessor->get_value($notification, 'memberprivileges');
				$params = array(
					'selected' => $selected
				);
				$privileges = new GD_URE_FormInput_FilterMemberPrivileges($params);
				return $privileges->get_selected_value();

			case 'membertags':

				$tags = GD_MetaManager::get_user_meta($notification->object_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

				// Filter status for the community: user_id
				return gd_ure_community_membershipstatus_filterbycommunity($tags, $notification->user_id);

			case 'membertags-strings' :				
				
				$selected = $fieldprocessor->get_value($notification, 'membertags');
				$params = array(
					'selected' => $selected
				);
				$tags = new GD_URE_FormInput_FilterMemberTags($params);
				return $tags->get_selected_value();
			// ----------------------------------------

			case 'icon' :
				
				// URL depends basically on the action performed on the object type
				switch ($notification->object_type) {

					case 'User':

						switch ($notification->action) {

							case URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY:
								return gd_navigation_menu_item(POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS, false);

							case URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP:
								return gd_navigation_menu_item(POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP, false);

							case URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES:
								return gd_navigation_menu_item(POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES, false);
						}
						break;
				}
				break;
			
			case 'url' :

				switch ($notification->object_type) {

					case 'User':

						switch ($notification->action) {

							case URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY:
							case URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP:
							case URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES:

								// Joined Community: link to the profile of the user joining
								// Updated User Membership: link to the profile of the community
								return get_author_posts_url($notification->user_id);

							// case URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP:

							// 	// Updated User Membership: link to the profile of the community, members tab
							// 	return GD_TemplateManager_Utils::add_tab(get_author_posts_url($notification->user_id), POP_URE_POPPROCESSORS_PAGE_MEMBERS);
						}
						break;
				}
				break;

			case 'message' :

				switch ($notification->object_type) {

					case 'User':

						switch ($notification->action) {

							case URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY:

								return sprintf(
									__('<strong>%s</strong> has joined <strong>%s</strong>', 'ure-popprocessors'),
									get_the_author_meta('display_name', $notification->user_id),
									get_the_author_meta('display_name', $notification->object_id)
								);
						
							case URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP:

								// Change the message depending if the logged in user is the object of this action
								$recipient = (get_current_user_id() == $notification->object_id) ? __('your', 'ure-popprocessors') : sprintf('<strong>%s</strong>’s', get_the_author_meta('display_name', $notification->object_id));
								return sprintf(
									__('<strong>%s</strong> has updated %s membership settings:', 'ure-popprocessors'),
									get_the_author_meta('display_name', $notification->user_id),
									$recipient
								);

							case URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES:

								$messages = array(
									URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES => __('<strong>%s</strong> updated the communities', 'ure-popprocessors'),
								);
								return sprintf(
									$messages[$notification->action],
									get_the_author_meta('display_name', $notification->user_id)
								);
						}
						break;
				}
				break;
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new URE_AAL_PoP_DataLoad_FieldProcessor_Notifications_Hook();