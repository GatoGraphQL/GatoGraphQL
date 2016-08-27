<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * MetaManager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_METAKEY_POST_AUTHORROLE', 'authorrole');

add_filter('gd_acf_get_keys_store_as_single', 'votingprocessors_acf_get_keys_store_as_single_custom');
function votingprocessors_acf_get_keys_store_as_single_custom($keys) {

	$keys[] = GD_URE_METAKEY_POST_AUTHORROLE;
	return $keys;
}
