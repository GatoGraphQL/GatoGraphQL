<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * ACF plugin Functions
 * 
 * Documentation: how ACF goes parallel to the user meta
 * Since we can't set ACF to have a format nice to read for the repeater field, so that it is compatible with get_user_meta and so we can filter by the selected values, so we decided to have both formats in the DB: ACF and the website custom format, thus creating redundancy. Then, whenever saving ACF from the back-end, it will then also save the custom format, and whenever saving the custom format from the front-end, it will trigger to also save ACF
 *
 * Documentation: Structure of the meta saved: it goes in each user (not in the community) and the format is community:status (privilege or tag)
 * It goes in the user so that we can search for it using get_users and meta_query values, and so that we can paginate it (if adding on the community, then doing get_user_meta returns all results, so it won't be paginaged, and we also cannot search for it). 
 * By having the format community:status, we can still use get_user_meta to filter all members that fulfil a requirement (eg: all approved users)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Store an array in different non-unique rows
if (is_admin()) {
	// Only execute in the back-end: when editing the Communities values using ACF,
	// then we gotta transform and also save these values for the template-manager needed format
	add_filter('acf/update_value', 'gd_ure_acf_communitymembership_update_customformat', 10, 3);
}
function gd_ure_acf_communitymembership_update_customformat($value, $post_id, $field) {

	$key = $field['name'];
	// if (in_array($key, gd_ure_acf_get_communitymembership_keys()) && $value) {
	if ($key == GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP) {

		$subfields = $field['sub_fields'];
		$keys = array();
		foreach ($subfields as $subfield) {

			$keys[$subfield['name']] = $subfield['key'];
		}

		$customformat_status = $customformat_privileges = $customformat_tags = array();
		if ($value) {
			foreach ($value as $index => $entry) {

				$community = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY]];
				$status = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS]];
				$privileges = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES]];
				$tags = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS]];

				// If the user clicked on '- Select -' in ACF it gives a value empty, so filter out this case using array_filter
				if (!is_array($privileges)) {
					$privileges = array($privileges);
				}
				$privileges = array_filter($privileges);
				if (!is_array($tags)) {
					$tags = array($tags);
				}
				$tags = array_filter($tags);

				// If the privileges or tags are empty, add 'empty' value
				// This is so that we can filter by this value on My Members' filter
				$customformat_status[] = gd_ure_get_community_metavalue($community, $status);
				if ($privileges) {
					foreach ($privileges as $privilege) {

						$customformat_privileges[] = gd_ure_get_community_metavalue($community, $privilege);
					}
				}
				else {
					$customformat_privileges[] = gd_ure_get_community_metavalue($community, GD_METAVALUE_NONE);
				}
				if ($tags) {
					foreach ($tags as $tag) {

						$customformat_tags[] = gd_ure_get_community_metavalue($community, $tag);
					}
				}
				else {
					$customformat_tags[] = gd_ure_get_community_metavalue($community, GD_METAVALUE_NONE);	
				}
			}
		}
		
		// This ACF Group is used in Users, so just go straight to that case
		// if( is_numeric($post_id) ) {

		// 	GD_MetaManager::update_post_meta($post_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, $customformat_status);
		// 	GD_MetaManager::update_post_meta($post_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, $customformat_privileges);
		// 	GD_MetaManager::update_post_meta($post_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS, $customformat_tags);
		// }
		// elseif( strpos($post_id, 'user_') !== false ) {

		$user_id = str_replace('user_', '', $post_id);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, $customformat_status);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, $customformat_privileges);
		GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS, $customformat_tags);
		// }
	}

	return $value;
}

// These will be executed in the front-end, not in the back-end:
// Transform from the custom format into ACF
add_action('ure:user:add_new_communities', 'gd_ure_acf_user_addnewcommunities', 10, 2);
function gd_ure_acf_user_addnewcommunities($user_id, $communities) {

	// Default values for when adding a new user to the community
	$status = GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE;
	$privileges = array(
		GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT
	);
	$tags = array(
		GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERTAGS_MEMBER
	);

	gd_ure_acf_user_updatecommunitiesmembership($user_id, $communities, $status, $privileges, $tags);
}

add_action('GD_EditMembership:update', 'gd_ure_acf_user_communitymembership_update', 10, 5);
function gd_ure_acf_user_communitymembership_update($user_id, $community, $status, $privileges, $tags) {

	gd_ure_acf_user_updatecommunitiesmembership($user_id, array($community), $status, $privileges, $tags);
}
function gd_ure_acf_user_updatecommunitiesmembership($user_id, $communities, $status, $privileges, $tags) {

	$acf_user_id = 'user_'.$user_id;

	// Taken from http://www.advancedcustomfields.com/resources/update_field/
	$value = get_field(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP, $acf_user_id);

	$update = true;
	if (!$value) {

		$update = false;
		$value = array();
	}

	// The entries for the current community might or might not exist: this function will be called both when the user is first created
	// (entries will be new) or when editing the user membership
	$found = array();
	foreach ($value as $index => $entry) {

		if (in_array($entry[GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY]['ID'], $communities)) {

			array_unshift($found, $index);
			continue;
		}
	}

	// Remove the current value(s) for this one community (should be only 1 value, however anyone on the back-end configuring through ACF could add the entry twice by mistake)
	foreach ($found as $found_index) {
		array_splice($value, $found_index, 1);
	}
	
	// Add again the settings for these communities
	foreach ($communities as $community) {

		$value[] = array(
			GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY => array(
				'ID' => $community
			),
			// GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY => gd_acf_get_formatteduser_from_id($community),
			GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS => $status,
			GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES => $privileges,
			GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS => $tags,
		);
	}

	// When creating a new user: if it is empty, we gotta create the value first
	// Taken from advanced-custom-fields/core/input.php function save_post( $post_id = 0 )
	// And called in function acf_save_post( $post_id = 0 ) {
	// do_action('acf/save_post', $post_id);
	// }	
	if ($update) {

		update_field(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP, $value, $acf_user_id);
	}
	else {

		$field = acf_get_field(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP);
		acf_update_value($value, $acf_user_id, $field);
	}
}

// function gd_acf_get_formatteduser_from_id($user_id) {
	
// 	$user_data = get_userdata($user_id);
			
// 	//cope with deleted users by @adampope
// 	if( !is_object($user_data) ) {
	
// 		return array();
// 	}

// 	// append to array
// 	$value = array();
// 	$value['ID'] = $user_id;
// 	$value['user_firstname'] = $user_data->user_firstname;
// 	$value['user_lastname'] = $user_data->user_lastname;
// 	$value['nickname'] = $user_data->nickname;
// 	$value['user_nicename'] = $user_data->user_nicename;
// 	$value['display_name'] = $user_data->display_name;
// 	$value['user_email'] = $user_data->user_email;
// 	$value['user_url'] = $user_data->user_url;
// 	$value['user_registered'] = $user_data->user_registered;
// 	$value['user_description'] = $user_data->user_description;
// 	$value['user_avatar'] = get_avatar( $user_id );

// 	return $value;
// }