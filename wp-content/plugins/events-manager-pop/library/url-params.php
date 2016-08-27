<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Important: can't define them as 'year' and 'month' because it creates trouble with WP, so named 'calendaryear'
define ('GD_URLPARAM_YEAR', 'calendaryear');
define ('GD_URLPARAM_MONTH', 'calendarmonth');

add_filter('gd_jquery_constants', 'gd_em_jquery_constants_urlparams');
function gd_em_jquery_constants_urlparams($jquery_constants) {

	$jquery_constants['URLPARAM_YEAR'] = GD_URLPARAM_YEAR;
	$jquery_constants['URLPARAM_MONTH'] = GD_URLPARAM_MONTH;
	
	return $jquery_constants;
}
