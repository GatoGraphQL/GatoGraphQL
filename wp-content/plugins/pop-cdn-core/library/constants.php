<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('POP_CDNCORE_URLPARAM_VERSION', 'v');
define ('GD_URLPARAM_CDNTHUMBPRINT', 'tp');
define ('POP_CDNCORE_THUMBPRINTVALUES', 'tpv');
define ('POP_CDNCORE_SEPARATOR_THUMBPRINT', '.');

add_filter('gd_jquery_constants', 'pop_cdncore_jquery_constants');
function pop_cdncore_jquery_constants($jquery_constants) {

	if (POP_CDN_CONTENT_URI) {

		// $jquery_constants['CDN_CONTENT_URI'] = POP_CDN_CONTENT_URI;
		$jquery_constants['CDN_URLPARAM_THUMBPRINT'] = GD_URLPARAM_CDNTHUMBPRINT;
		$jquery_constants['CDN_THUMBPRINTVALUES'] = POP_CDNCORE_THUMBPRINTVALUES;
		$jquery_constants['CDN_SEPARATOR_THUMBPRINT'] = POP_CDNCORE_SEPARATOR_THUMBPRINT;
	}
	return $jquery_constants;
}
