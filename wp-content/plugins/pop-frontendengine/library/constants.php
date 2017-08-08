<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_STRING_MORE', __('more...', 'pop-frontendengine'));
define ('GD_STRING_LESS', __('less...', 'pop-frontendengine'));

define ('POP_PARAMS_PARAMSSCOPE_URL', 'paramsscope-url');
define ('POP_PARAMS_TOPLEVEL_URL', 'toplevel-url');
define ('POP_PARAMS_TOPLEVEL_DOMAIN', 'toplevel-domain');

add_filter('gd_jquery_constants', 'pop_frontend_jquery_constants_impl');
function pop_frontend_jquery_constants_impl($jquery_constants) {

	$jquery_constants['PARAMS_PARAMSSCOPE_URL'] = POP_PARAMS_PARAMSSCOPE_URL;
	$jquery_constants['PARAMS_TOPLEVEL_URL'] = POP_PARAMS_TOPLEVEL_URL;
	$jquery_constants['PARAMS_TOPLEVEL_DOMAIN'] = POP_PARAMS_TOPLEVEL_DOMAIN;
	return $jquery_constants;
}
