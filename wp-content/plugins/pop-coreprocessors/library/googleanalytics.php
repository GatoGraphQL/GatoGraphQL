<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Google Analytics
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_jquery_constants_googleanalytics');
function gd_jquery_constants_googleanalytics($jquery_constants) {

	$key = apply_filters('gd_googleanalytics_key', '');
	
	if ($key) {
		$jquery_constants['GOOGLEANALYTICS'] = $key;
	}
	
	return $jquery_constants;
}