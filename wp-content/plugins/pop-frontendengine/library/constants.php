<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_STRING_MORE', __('more...', 'pop-frontendengine'));
define ('GD_STRING_LESS', __('less...', 'pop-frontendengine'));
define ('GD_SEPARATOR_RESOURCELOADER', '|');
define ('POP_VALUES_DEFAULT', 'default');

define ('POP_PARAMS_PARAMSSCOPE_URL', 'paramsscope-url');
define ('POP_PARAMS_TOPLEVEL_URL', 'toplevel-url');
define ('POP_PARAMS_TOPLEVEL_DOMAIN', 'toplevel-domain');

define ('POP_PROGRESSIVEBOOTING_CRITICAL', PoP_ServerUtils::compact_js_keys() ? 'c' : 'critical');
define ('POP_PROGRESSIVEBOOTING_NONCRITICAL', PoP_ServerUtils::compact_js_keys() ? 'n' : 'noncritical');

add_filter('gd_jquery_constants', 'pop_frontend_jquery_constants_impl');
function pop_frontend_jquery_constants_impl($jquery_constants) {

	$jquery_constants['VALUES_DEFAULT'] = POP_VALUES_DEFAULT;
	$jquery_constants['SEPARATOR_RESOURCELOADER'] = GD_SEPARATOR_RESOURCELOADER;
	$jquery_constants['PARAMS_PARAMSSCOPE_URL'] = POP_PARAMS_PARAMSSCOPE_URL;
	$jquery_constants['PARAMS_TOPLEVEL_URL'] = POP_PARAMS_TOPLEVEL_URL;
	$jquery_constants['PARAMS_TOPLEVEL_DOMAIN'] = POP_PARAMS_TOPLEVEL_DOMAIN;

	// Comment Leo20/11/2017: add these constants always, since they are referenced in the JS code even if Progressive Booting is not enabled
	// if (PoP_Frontend_ServerUtils::use_progressive_booting()) {
	$jquery_constants['PROGRESSIVEBOOTING'] = array(
		'CRITICAL' => POP_PROGRESSIVEBOOTING_CRITICAL,
		'NONCRITICAL' => POP_PROGRESSIVEBOOTING_NONCRITICAL,
	);
	// }
	
	return $jquery_constants;
}
