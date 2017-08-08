<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * qTranslate plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add the locale to all the multidomains
add_filter('gd_templatemanager:multidomain:locale', 'wassup_qtrans_set_locale', 0, 2);
function wassup_qtrans_set_locale($locale, $domain) {

	// By default, add the current language, after the domain, to all domains
	// Then, this can be overriden on a domain by domain basis
	return $domain.'/'.qtranxf_getLanguage().'/';
}