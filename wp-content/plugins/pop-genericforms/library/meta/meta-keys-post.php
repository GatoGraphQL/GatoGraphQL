<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_POST_VOLUNTEERSNEEDED', 'volunteersneeded');

add_filter('gd_acf_get_keys_store_as_single', 'gd_acf_get_keys_store_as_single_custom_impl');
function gd_acf_get_keys_store_as_single_custom_impl($keys) {

	$keys[] = GD_METAKEY_POST_VOLUNTEERSNEEDED;
	return $keys;
}
