<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_RESOURCETYPE_JS', 'js');
define ('POP_RESOURCELOADER_RESOURCETYPE_CSS', 'css');

define ('POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL', 'normal');
define ('POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR', 'vendor');
define ('POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC', 'dynamic');
define ('POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE', 'template');

define ('POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE', 'immediate');
define ('POP_RESOURCELOADER_LOADINGTYPE_ASYNC', 'async');
define ('POP_RESOURCELOADER_LOADINGTYPE_DEFER', 'defer');

add_filter('gd_jquery_constants', 'pop_frontend_resourceloader_jquery_constants');
function pop_frontend_resourceloader_jquery_constants($jquery_constants) {

	if (PoP_Frontend_ServerUtils::use_code_splitting()) {
		
		$jquery_constants['RESOURCELOADER'] = array(
			'TYPES' => array(
				'JS' => POP_RESOURCELOADER_RESOURCETYPE_JS,
				'CSS' => POP_RESOURCELOADER_RESOURCETYPE_CSS,
			),
		);
	}
	
	return $jquery_constants;
}
