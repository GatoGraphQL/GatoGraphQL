<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_POST_FARMCATEGORIES', 'farmcategories');

add_filter('gd_acf_get_keys_store_as_array', 'op_acf_get_keys_store_as_array');
function op_acf_get_keys_store_as_array($keys) {

	$keys[] = GD_METAKEY_POST_FARMCATEGORIES;
	return $keys;
}
