<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_URLPARAM_ACTION_PRINT', 'print');

define ('GD_URLPARAM_TARGET_PRINT', 'print');
define ('GD_URLPARAM_TARGET_SOCIALMEDIA', 'socialmedia');

add_filter('gd_jquery_constants', 'gd_jquery_constants_urlparams');
function gd_jquery_constants_urlparams($jquery_constants) {

	$jquery_constants['UNIQUEID'] = POP_UNIQUEID;
	// $jquery_constants['ORIGINALUNIQUEID'] = POP_ORIGINALUNIQUEID;

	$jquery_constants['URLPARAM_TIMESTAMP'] = GD_URLPARAM_TIMESTAMP;	
	$jquery_constants['URLPARAM_ACTION'] = GD_URLPARAM_ACTION;	
	$jquery_constants['URLPARAM_ACTION_LATEST'] = GD_URLPARAM_ACTION_LATEST;	
	$jquery_constants['URLPARAM_ACTION_PRINT'] = GD_URLPARAM_ACTION_PRINT;	
	// $jquery_constants['URLPARAM_SKIPPARAMS'] = GD_URLPARAM_SKIPPARAMS;	

	$jquery_constants['URLPARAM_THEMEMODE'] = GD_URLPARAM_THEMEMODE;	
	$jquery_constants['URLPARAM_THEMESTYLE'] = GD_URLPARAM_THEMESTYLE;	

	$jquery_constants['URLPARAM_PARENTPAGEID'] = GD_URLPARAM_PARENTPAGEID;
	$jquery_constants['URLPARAM_TITLE'] = GD_URLPARAM_TITLE;
	$jquery_constants['URLPARAM_TITLELINK'] = GD_URLPARAM_TITLELINK;
	$jquery_constants['URLPARAM_URL'] = GD_URLPARAM_URL;
	$jquery_constants['URLPARAM_ERROR'] = GD_URLPARAM_ERROR;
	$jquery_constants['URLPARAM_SILENTDOCUMENT'] = GD_URLPARAM_SILENTDOCUMENT;
	$jquery_constants['URLPARAM_STORELOCAL'] = GD_URLPARAM_STORELOCAL;
	
	$jquery_constants['URLPARAM_BACKGROUNDLOADURLS'] = GD_URLPARAM_BACKGROUNDLOADURLS;
	
	$jquery_constants['URLPARAM_OUTPUT'] = GD_URLPARAM_OUTPUT;
	$jquery_constants['URLPARAM_OUTPUT_JSON'] = GD_URLPARAM_OUTPUT_JSON;
	$jquery_constants['URLPARAM_IDFORMAT'] = POP_URLPARAM_IDFORMAT;
	$jquery_constants['URLPARAM_IDFORMAT_ORIGINAL'] = POP_URLPARAM_IDFORMAT_ORIGINAL;
	
	$jquery_constants['URLPARAM_PAGED'] = GD_URLPARAM_PAGED;
	$jquery_constants['URLPARAM_OPERATION_APPEND'] = GD_URLPARAM_OPERATION_APPEND;
	$jquery_constants['URLPARAM_OPERATION_PREPEND'] = GD_URLPARAM_OPERATION_PREPEND;
	$jquery_constants['URLPARAM_OPERATION_REPLACE'] = GD_URLPARAM_OPERATION_REPLACE;
	$jquery_constants['URLPARAM_OPERATION_REPLACEINLINE'] = GD_URLPARAM_OPERATION_REPLACEINLINE;

	$jquery_constants['URLPARAM_MODULE'] = GD_URLPARAM_MODULE;
	$jquery_constants['URLPARAM_MODULE_SETTINGSDATA'] = GD_URLPARAM_MODULE_SETTINGSDATA;
	$jquery_constants['URLPARAM_MODULE_DATA'] = GD_URLPARAM_MODULE_DATA;
	$jquery_constants['URLPARAM_TARGET'] = GD_URLPARAM_TARGET;
	$jquery_constants['URLPARAM_TARGET_MAIN'] = GD_URLPARAM_TARGET_MAIN;
	$jquery_constants['URLPARAM_TARGET_FULL'] = GD_URLPARAM_TARGET_FULL;
	$jquery_constants['URLPARAM_TARGET_PRINT'] = GD_URLPARAM_TARGET_PRINT;
	$jquery_constants['URLPARAM_TARGET_SOCIALMEDIA'] = GD_URLPARAM_TARGET_SOCIALMEDIA;
	
	$jquery_constants['URLPARAM_VALIDATECHECKPOINTS'] = GD_URLPARAM_VALIDATECHECKPOINTS;

	$jquery_constants['URLPARAM_HIDEBLOCK'] = GD_URLPARAM_HIDEBLOCK;
	$jquery_constants['URLPARAM_STOPFETCHING'] = GD_URLPARAM_STOPFETCHING;

	return $jquery_constants;
}
