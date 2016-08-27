<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * All Date stuff
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_jquery_constants_multiselect_impl');
function gd_jquery_constants_multiselect_impl($jquery_constants) {

	$jquery_constants['MULTISELECT_NONSELECTEDTEXT'] = __('None selected', 'pop-coreprocessors');
	$jquery_constants['MULTISELECT_NSELECTEDTEXT'] = __('selected', 'pop-coreprocessors');
	$jquery_constants['MULTISELECT_ALLSELECTEDTEXT'] = __('All selected', 'pop-coreprocessors');
	
	return $jquery_constants;
}