<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_METAKEY_POST_DISCLAIMERURL', 'disclaimer_url');

define ('GD_METAKEY_POST_LINKACCESS', 'linkaccess');
define ('GD_METAKEY_POST_LINKCATEGORIES', 'linkcategories');
define ('GD_METAKEY_POST_CATEGORIES', 'categories');
define ('GD_METAKEY_POST_APPLIESTO', 'appliesto');

add_filter('gd_acf_get_keys_store_as_array', 'wassup_acf_get_keys_store_as_array_custom');
function wassup_acf_get_keys_store_as_array_custom($keys) {

	$keys[] = GD_METAKEY_POST_LINKCATEGORIES;
	$keys[] = GD_METAKEY_POST_CATEGORIES;
	$keys[] = GD_METAKEY_POST_APPLIESTO;
	return $keys;
}

add_filter('gd_acf_get_keys_store_as_single', 'wassup_acf_get_keys_store_as_single_custom');
function wassup_acf_get_keys_store_as_single_custom($keys) {

	$keys[] = GD_METAKEY_POST_LINKACCESS;
	return $keys;
}
