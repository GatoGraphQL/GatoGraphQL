<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

$compact = PoP_ServerUtils::compact_js_keys();
define ('GD_JS_FONTAWESOME', $compact ? 'fa' : 'fontawesome');
define ('GD_JS_DESCRIPTION', $compact ? 'd' : 'description');

add_filter('gd_jquery_constants', 'wassup_jquery_constants_jsparams');
function wassup_jquery_constants_jsparams($jquery_constants) {

	$jquery_constants['JS_FONTAWESOME'] = GD_JS_FONTAWESOME;
	$jquery_constants['JS_DESCRIPTION'] = GD_JS_DESCRIPTION;

	return $jquery_constants;
}

