<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_TERM_SUBSCRIBEDBY', 'subscribedby');
define ('GD_METAKEY_TERM_SUBSCRIBERSCOUNT', 'subscribers_count');

add_filter('gd_acf_get_keys_store_as_array', 'gd_acf_get_keys_store_as_array_terms');
function gd_acf_get_keys_store_as_array_terms($keys) {

	$keys[] = GD_METAKEY_TERM_SUBSCRIBEDBY;
	return $keys;
}