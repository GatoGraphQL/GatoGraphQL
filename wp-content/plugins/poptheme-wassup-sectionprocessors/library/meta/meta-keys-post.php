<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_POST_LOCATIONPOSTCATEGORIES', 'locationpostcategories');
define ('GD_METAKEY_POST_DISCUSSIONCATEGORIES', 'discussioncategories');

add_filter('gd_acf_get_keys_store_as_array', 'gd_acf_get_keys_store_as_array_custom_impl');
function gd_acf_get_keys_store_as_array_custom_impl($keys) {

	$keys[] = GD_METAKEY_POST_LOCATIONPOSTCATEGORIES;
	$keys[] = GD_METAKEY_POST_DISCUSSIONCATEGORIES;
	return $keys;
}
