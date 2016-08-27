<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_COMMENT_TAGGEDUSERS', 'taggedusers');

add_filter('gd_acf_get_keys_store_as_array', 'gd_acf_get_keys_store_as_array_comments');
function gd_acf_get_keys_store_as_array_comments($keys) {

	$keys[] = GD_METAKEY_COMMENT_TAGGEDUSERS;
	return $keys;
}

