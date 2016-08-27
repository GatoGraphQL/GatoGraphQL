<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_POST_VOLUNTEERSNEEDED', 'volunteersneeded');
define ('GD_METAKEY_POST_REFERENCES', 'references');
define ('GD_METAKEY_POST_RECOMMENDEDBY', 'recommendedby');
define ('GD_METAKEY_POST_RECOMMENDCOUNT', 'recommend_count');
define ('GD_METAKEY_POST_UPVOTEDBY', 'upvotedby');
define ('GD_METAKEY_POST_UPVOTECOUNT', 'upvote_count');
define ('GD_METAKEY_POST_DOWNVOTEDBY', 'downvotedby');
define ('GD_METAKEY_POST_DOWNVOTECOUNT', 'downvote_count');

define ('GD_METAKEY_POST_TAGGEDUSERS', 'taggedusers');

define ('GD_METAKEY_POST_LOCATIONS', 'locations');

add_filter('gd_acf_get_keys_store_as_array', 'gd_acf_get_keys_store_as_array_posts');
function gd_acf_get_keys_store_as_array_posts($keys) {

	$keys[] = GD_METAKEY_POST_LOCATIONS;
	$keys[] = GD_METAKEY_POST_REFERENCES;
	$keys[] = GD_METAKEY_POST_RECOMMENDEDBY;
	$keys[] = GD_METAKEY_POST_UPVOTEDBY;
	$keys[] = GD_METAKEY_POST_DOWNVOTEDBY;
	$keys[] = GD_METAKEY_POST_TAGGEDUSERS;
	return $keys;
}

add_filter('gd_acf_get_keys_store_as_single', 'gd_acf_get_keys_store_as_single_custom_impl');
function gd_acf_get_keys_store_as_single_custom_impl($keys) {

	$keys[] = GD_METAKEY_POST_VOLUNTEERSNEEDED;
	return $keys;
}
