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
define ('GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS', 'subscribes_to_tags');
define ('GD_METAKEY_PROFILE_UPVOTESPOSTS', 'upvotes_posts');
define ('GD_METAKEY_PROFILE_DOWNVOTESSPOSTS', 'downvotes_posts');
define ('GD_METAKEY_PROFILE_FOLLOWEDBY', 'followedby');
define ('GD_METAKEY_PROFILE_FOLLOWERSCOUNT', 'followers_count');

// My preferences
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_NEWPOST' , 'pref_emailnotif_general_newpost');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST' , 'pref_emailnotif_general_specialpost');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST' , 'pref_emailnotif_network_createdpost');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST' , 'pref_emailnotif_network_recommendedpost');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER' , 'pref_emailnotif_network_followeduser');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC' , 'pref_emailnotif_network_subscribedtotopic');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT' , 'pref_emailnotif_network_addedcomment');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST' , 'pref_emailnotif_network_updownvotedpost');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST' , 'pref_emailnotif_subscribedtopic_createdpost');
define ('GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT' , 'pref_emailnotif_subscribedtopic_addedcomment');
define ('GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYCONTENT' , 'pref_emaildigests_dailynewcontent');
define ('GD_METAKEY_PROFILE_EMAILDIGESTS_BIWEEKLYUPCOMINGEVENTS' , 'pref_emaildigests_biweeklyupcomingevents');
define ('GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYNETWORKACTIVITY' , 'pref_emaildigests_dailynetworkactivity');
define ('GD_METAKEY_PROFILE_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY' , 'pref_emaildigests_dailysubscribedtopicsactivity');

add_filter('gd_acf_get_keys_store_as_array', 'gd_acf_get_keys_store_as_array_profiles');
function gd_acf_get_keys_store_as_array_profiles($keys) {

	$keys[] = GD_METAKEY_PROFILE_LOCATIONS;
	$keys[] = GD_METAKEY_PROFILE_FOLLOWSUSERS;
	$keys[] = GD_METAKEY_PROFILE_RECOMMENDSPOSTS;
	$keys[] = GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS;
	$keys[] = GD_METAKEY_PROFILE_UPVOTESPOSTS;
	$keys[] = GD_METAKEY_PROFILE_DOWNVOTESSPOSTS;
	$keys[] = GD_METAKEY_PROFILE_FOLLOWEDBY;
	return $keys;
}