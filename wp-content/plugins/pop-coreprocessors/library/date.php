<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * All Date stuff
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define('GD_DATERANGE_SEPARATOR', ' - ');

add_filter('gd_jquery_constants', 'gd_jquery_constants_daterange_impl');
function gd_jquery_constants_daterange_impl($jquery_constants) {

	$jquery_constants['DATERANGE_SEPARATOR'] = GD_DATERANGE_SEPARATOR;
	
	/*
	$jquery_constants['DATERANGE_TODAY'] = __('Today', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_THISMONTH'] = __('This Month', 'pop-coreprocessors');
	
	$jquery_constants['DATERANGE_YESTERDAY'] = __('Yesterday', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_LAST7DAYS'] = __('Last 7 Days', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_LAST30DAYS'] = __('Last 30 Days', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_LASTMONTH'] = __('Last Month', 'pop-coreprocessors');
	
	$jquery_constants['DATERANGE_TOMORROW'] = __('Tomorrow', 'pop-coreprocessors');	
	$jquery_constants['DATERANGE_NEXT7DAYS'] = __('Next 7 Days', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_NEXT30DAYS'] = __('Next 30 Days', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_NEXTMONTH'] = __('Next Month', 'pop-coreprocessors');
	*/
	$jquery_constants['DATERANGE_APPLY'] = __('Apply', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_CANCEL'] = __('Cancel', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_FROM'] = __('From', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_TO'] = __('To', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_CUSTOMRANGE'] = __('Custom Range', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_FORMAT'] = __('DD/MM/YYYY', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_TIMEFORMAT'] = __('h:mm A', 'pop-coreprocessors');
	$jquery_constants['DATERANGE_DAYSOFWEEK'] = array(
		__('Su', 'pop-coreprocessors'), 
		__('Mo', 'pop-coreprocessors'),
		__('Tu', 'pop-coreprocessors'),
		__('We', 'pop-coreprocessors'),
		__('Th', 'pop-coreprocessors'),
		__('Fr', 'pop-coreprocessors'),
		__('Sa', 'pop-coreprocessors'),
	);	
	$jquery_constants['DATERANGE_MONTHNAMES'] = array(
		__('January', 'pop-coreprocessors'), 
		__('February', 'pop-coreprocessors'),
		__('March', 'pop-coreprocessors'),
		__('April', 'pop-coreprocessors'),
		__('May', 'pop-coreprocessors'),
		__('June', 'pop-coreprocessors'),
		__('July', 'pop-coreprocessors'),
		__('August', 'pop-coreprocessors'),
		__('September', 'pop-coreprocessors'),
		__('October', 'pop-coreprocessors'),
		__('November', 'pop-coreprocessors'),
		__('December', 'pop-coreprocessors'),
	);
	
	return $jquery_constants;
}