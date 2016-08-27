<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_ure_aal_jquery_constants_urlparams');
function gd_ure_aal_jquery_constants_urlparams($jquery_constants) {

	$jquery_constants['ACTION_USER_UPDATEDUSERMEMBERSHIP'] = URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP;
	$jquery_constants['ACTION_USER_JOINEDCOMMUNITY'] = URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY;
	$jquery_constants['OBJECTTYPE_USER'] = 'User';
	
	return $jquery_constants;
}
