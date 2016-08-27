<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Meta Manager 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**--------------------------------------------------------
 * Meta Keys 
 --------------------------------------------------------**/

define ('GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES', 'organizationcategories');
define ('GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES', 'organizationtypes');
define ('GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS', 'individualinterests');

define ('GD_URE_METAKEY_PROFILE_CONTACTPERSON', 'contact_person');
define ('GD_URE_METAKEY_PROFILE_CONTACTNUMBER', 'contact_number');

add_filter('gd_acf_get_keys_store_as_array', 'gd_ure_acf_get_keys_store_as_array_custom_impl');
function gd_ure_acf_get_keys_store_as_array_custom_impl($keys) {

	$keys[] = GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES;
	$keys[] = GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES;
	$keys[] = GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS;
	return $keys;
}