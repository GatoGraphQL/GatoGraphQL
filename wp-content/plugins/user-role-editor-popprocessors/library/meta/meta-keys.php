<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Meta keys 
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**--------------------------------------------------------
 * COMMUNITIES 
 --------------------------------------------------------**/
define ('GD_URE_METAKEY_PROFILE_COMMUNITIES', 'communities');
define ('GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS', 'memberstatus');
define ('GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES', 'memberprivileges');
define ('GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS', 'membertags');

/**--------------------------------------------------------
 * Meta values 
 --------------------------------------------------------**/
define ('GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE', 'active');
define ('GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_REJECTED', 'rejected');
define ('GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT', 'contributecontent');
define ('GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERTAGS_MEMBER', 'member');


add_filter('gd_acf_get_keys_store_as_array', 'gd_popure_acf_get_keys_store_as_array_custom_impl');
function gd_popure_acf_get_keys_store_as_array_custom_impl($keys) {

	$keys[] = GD_URE_METAKEY_PROFILE_COMMUNITIES;
	return $keys;
}