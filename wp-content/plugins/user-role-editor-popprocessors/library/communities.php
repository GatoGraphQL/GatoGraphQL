<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Communities functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_ure_get_communities($user_id) {

	return GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES);
}

// Hook the user's network function, adding the users belonging to the same communities as the user
add_filter('get_user_networkusers', 'gd_ure_get_user_networkusers', 10, 2);
function gd_ure_get_user_networkusers($usernetwork, $user_id) {

	if ($communities = gd_ure_get_communities_status_active($user_id)) {

		// // Add the communities also to the user's network
		// $usernetwork = array_merge(
		// 	$usernetwork,
		// 	$communities
		// );

		// Get all the active members of those communities
		foreach ($communities as $community) {
			
			$community_members = URE_CommunityUtils::get_community_members($community);
			$usernetwork = array_merge(
				$usernetwork,
				$community_members
			);
		}

		// Remove duplicates
		$usernetwork = array_unique($usernetwork);

		// Remove the user him/herself (since the user is also a member of his/her communities)
		$pos = array_search($user_id, $usernetwork);
		if ($pos > -1) {
			array_splice($usernetwork, $pos, 1);
		}
	}

	return $usernetwork;
}


// function gd_ure_get_communitymembers($community) {

// 	$key = GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, GD_META_TYPE_USER);

// 	// Taken from https://codex.wordpress.org/Class_Reference/WP_Meta_Query
// 	$query = array(
// 		'fields' => 'ID',
// 		'meta_query' => array(
// 			'relation' => 'AND',
// 			array(
// 				'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, GD_META_TYPE_USER),
// 				'value' => gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE),
// 				'compare' => 'IN'
// 			),
// 			array(
// 				'key' => $key,
// 				'value' => gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT),
// 				'compare' => 'IN'
// 			)
// 		)
// 	);

// 	return get_users($query);
// }	

function gd_ure_get_activecontributingcontentcommunitymembers($community) {

	// Taken from https://codex.wordpress.org/Class_Reference/WP_Meta_Query

	// It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
	// And the Organization must've accepted it by leaving the Contribute Content privilege on
	$query = array(
		'fields' => 'ID',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES, GD_META_TYPE_USER),
				'value' => $community,
				'compare' => 'IN'
			),
			array(
				'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, GD_META_TYPE_USER),
				'value' => gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE),
				'compare' => 'IN'
			),
			array(
				'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, GD_META_TYPE_USER),
				'value' => gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT),
				'compare' => 'IN'
			)
		)
	);

	return get_users($query);
}	

function gd_ure_get_community_metavalue_contributecontent($user_id) {

	return gd_ure_get_community_metavalue($user_id, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT);
}

function gd_ure_get_community_metavalue_currentcommunity($value) {

	$vars = GD_TemplateManager_Utils::get_vars();
	$community = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
	return gd_ure_get_community_metavalue($community, $value);
}

function gd_ure_get_community_metavalue($user_id, $value) {

	return $user_id.':'.$value;
}

function gd_ure_user_addnewcommunities($user_id, $communities) {

	// Make sure there are communities
	if (!$communities) return;

	$status = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
	$privileges = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);
	$tags = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);
	
	// When creating a new user account, these will be empty, so get the default ones
	if (!$status) {
		$status = $privileges = $tags = array();
	}

	// For each community, add the default privileges/tags (only if not existing already, so as to not override values already set by the community)
	// This also works because we've set GD_METAVALUE_NONE if they are empty, so that it will never have no value in the DB
	foreach ($communities as $community) {

		$status[] = gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE);

		// Add the default privileges for this one community
		$privileges[] = gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT);
		
		// Add the default tags for this one community
		$tags[] = gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERTAGS_MEMBER);
	}

	// Update the DB
	GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, $status);
	GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, $privileges);
	GD_MetaManager::update_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS, $tags);

	// Allow ACF to also save the value in the DB
	do_action('ure:user:add_new_communities', $user_id, $communities);
}

function gd_ure_find_community_metavalues($community, $values, $extract_metavalue = true) {

		$ret = array();

		// Filter to retrieve only the status for the given community
		if ($values) {
			foreach ($values as $value) {
				
				$parts = explode(':', $value);
				
				// Found a record for this community?
				if ($community == $parts[0]) {

					// Found!
					if ($extract_metavalue) {
						$ret[] = $parts[1];
					}
					else {
						$ret[] = $value;
					}
				}
			}
		}

		return $ret;
	}

function gd_ure_get_communities_status_active($user_id) {

	// Filter the community roles where the user is accepted as a member
	if ($community_status = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS)) {
		
		$statusactive_communities = array_values(array_filter(array_map('gd_ure_get_communities_status_active_filter', $community_status)));
		
		// Get the communities the user says he/she belongs to
		$userchosen_communities = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES);

		// Return the intersection of these 2
		return array_intersect($statusactive_communities, $userchosen_communities);
	}

	return array();
}

function gd_ure_get_communities_status_active_filter($value) {

	$parts = explode(':', $value);
	$community = $parts[0];
	$status = $parts[1];

	if ($status == GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE) {
		return $community;
	}

	return false;
}

function gd_ure_community_membershipstatus_filterbycurrentcommunity($values) {

	$vars = GD_TemplateManager_Utils::get_vars();
	return gd_ure_community_membershipstatus_filterbycommunity($values, $vars['global-state']['current-user-id']/*get_current_user_id()*/);
}

function gd_ure_community_membershipstatus_filterbycommunity($values, $community) {

	$ret = array();
	foreach ($values as $value) {

		$parts = explode(':', $value);
		$usercommunity = $parts[0];
		if ($community == $usercommunity) {
			$status = $parts[1]; // Status can be the privilege or tag
			$ret[] = $status;
		}
	}

	return $ret;
}

// function gd_ure_community_membershipstatus_filterbycurrentcommunity($value) {

// 	// using array_values so that the original key array index key is discarded, so it's not treated as an object by jQuery but as an array
// 	return array_values(array_filter(array_map('gd_ure_community_membershipstatus_filterbycurrentcommunity_mapfn', $value)));
// }

// function gd_ure_community_membershipstatus_filterbycurrentcommunity_mapfn($value) {

// 	$parts = explode(':', $value);
// 	$usercommunity = $parts[0];
// 	$status = $parts[1]; // Status can be the privilege or tag

// 	// Filter privileges for this community: the logged in user
	// $vars = GD_TemplateManager_Utils::get_vars();
// 	$community = $vars['global-state']['current-user-id']/*get_current_user_id()*/;

// 	if ($community == $usercommunity) {
// 		return $status;
// 	}

// 	return false;
// }

function gd_ure_edit_membership_url($user_id, $inline = false) {

	$url = get_permalink(POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP);
	
	$nonce = gd_create_nonce(GD_NONCE_EDITMEMBERSHIPURL, $user_id);
	$url = add_query_arg('_wpnonce', $nonce, $url);
	$url = add_query_arg('uid', $user_id, $url);
	// $url = add_query_arg(GD_TEMPLATE_FORMCOMPONENT_NONCE, $nonce, $url);
	// $url = add_query_arg(GD_TEMPLATE_FORMCOMPONENT_USERID, $user_id, $url);
				
	return $url;
}