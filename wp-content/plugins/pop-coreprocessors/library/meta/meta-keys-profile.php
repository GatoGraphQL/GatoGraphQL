<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_PROFILE_TITLE', 'title');
define ('GD_METAKEY_PROFILE_DISPLAYEMAIL', 'display_email');
define ('GD_METAKEY_PROFILE_SHORTDESCRIPTION', 'shortdescription');

define ('GD_METAKEY_PROFILE_LOCATIONS', 'locations');
define ('GD_METAKEY_PROFILE_FOLLOWSUSERS', 'follows_users');
define ('GD_METAKEY_PROFILE_RECOMMENDSPOSTS', 'recommends_posts');
define ('GD_METAKEY_PROFILE_UPVOTESPOSTS', 'upvotes_posts');
define ('GD_METAKEY_PROFILE_DOWNVOTESSPOSTS', 'downvotes_posts');
define ('GD_METAKEY_PROFILE_FOLLOWEDBY', 'followedby');
define ('GD_METAKEY_PROFILE_FOLLOWERSCOUNT', 'followers_count');

add_filter('gd_acf_get_keys_store_as_array', 'gd_acf_get_keys_store_as_array_profiles');
function gd_acf_get_keys_store_as_array_profiles($keys) {

	$keys[] = GD_METAKEY_PROFILE_LOCATIONS;
	$keys[] = GD_METAKEY_PROFILE_FOLLOWSUSERS;
	$keys[] = GD_METAKEY_PROFILE_RECOMMENDSPOSTS;
	$keys[] = GD_METAKEY_PROFILE_UPVOTESPOSTS;
	$keys[] = GD_METAKEY_PROFILE_DOWNVOTESSPOSTS;
	$keys[] = GD_METAKEY_PROFILE_FOLLOWEDBY;
	return $keys;
}