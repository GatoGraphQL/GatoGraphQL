<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Constants
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CONSTANT_LOADING_MSG', __('Loading', 'pop-coreprocessors'));
define ('GD_CONSTANT_ERROR_MSG', '<i class="fa fa-fw fa-warning"></i>'.__('Ops, there was a connection problem.', 'pop-coreprocessors'));
define ('GD_CONSTANT_OFFLINE_MSG', '<i class="fa fa-fw fa-warning"></i>'.__('It seems you are offline.', 'pop-coreprocessors'));
define ('GD_CONSTANT_RETRY_MSG', __('Retry', 'pop-coreprocessors'));
define ('GD_CONSTANT_LOADING_SPINNER', '<i class="fa fa-fw fa-spinner fa-spin"></i>');
define ('GD_CONSTANT_AUTHORS_SEPARATOR', '<span class="preview-author-sep">|</span>');
define ('GD_CONSTANT_MANDATORY', '*');
define ('GD_SEPARATOR', ',');
define ('GD_CONSTANT_FEEDBACKMSG_MULTIDOMAIN', __('(<em>{0}</em>) {1}', 'pop-coreprocessors'));
// define (
// 	'GD_CONSTANT_LAZYLOAD_LOADINGDIV', 
// 	sprintf(
// 		'<div class="pop-lazyload-loading">%s</div>',
// 		GD_CONSTANT_LOADING_SPINNER.' '.__('Loading data', 'pop-coreprocessors')
// 	)
// );

// define ('POP_MULTILAYOUT_TYPE_POST', 'post');
// define ('POP_MULTILAYOUT_TYPE_USER', 'user');
// // define ('POP_MULTILAYOUT_TYPE_NOTIFICATION', 'notification');

define ('GD_SETTINGS_PARAMSSCOPE_URL', 'url');

define ('GD_TEMPLATEID_PAGESECTIONGROUP_ID', 'pagesection-group');
define ('GD_TEMPLATEID_QUICKVIEWPAGESECTIONGROUP_ID', 'quickviewpagesection-group');

// wpEditor
define ('GD_TEMPLATESETTINGS_EDITOR_NAME', 'wpeditorcomponent');

// define ('GD_INTERCEPTOR_WITHPARAMS', 'with-params');
define ('GD_JS_INITIALIZED', 'js-initialized');
define ('GD_CRITICALJS_INITIALIZED', 'criticaljs-initialized');

define ('GD_TEMPLATECALLBACK_ACTION_LOADCONTENT', 'loadcontent');
define ('GD_TEMPLATECALLBACK_ACTION_REFETCH', 'refetch');
define ('GD_TEMPLATECALLBACK_ACTION_RESET', 'reset');

define ('GD_JSPLACEHOLDER_QUERY', '*QUERY*'); // Replaced from '%QUERY' because using '%' gives a JS error (Uncaught URIError: URI malformed) on function splitParams in utils.js when trying to add yet another parameter on that URL 

define('POP_KEYS_THUMBPRINT', 'thumbprint');

add_filter('gd_jquery_constants', 'gd_popcore_jquery_constants_templatemanager_impl');
function gd_popcore_jquery_constants_templatemanager_impl($jquery_constants) {

	$jquery_constants['JSPLACEHOLDER_QUERY'] = GD_JSPLACEHOLDER_QUERY;
	
	// ------------------------------------------
	// Constants from PoP (plugins/pop/kernel/library/constants.php)
	// ------------------------------------------
	$jquery_constants['ID_SEPARATOR'] = POP_CONSTANT_ID_SEPARATOR;
	$jquery_constants['TEMPLATE_TOPLEVEL_SETTINGS_ID'] = GD_TEMPLATEID_TOPLEVEL_SETTINGSID;
	$jquery_constants['JSMETHOD_GROUP_MAIN'] = GD_JSMETHOD_GROUP_MAIN;
	// ------------------------------------------

	// $jquery_constants['MULTILAYOUT_TYPE_POST'] = POP_MULTILAYOUT_TYPE_POST;
	// $jquery_constants['MULTILAYOUT_TYPE_USER'] = POP_MULTILAYOUT_TYPE_USER;
	// // $jquery_constants['MULTILAYOUT_TYPE_NOTIFICATION'] = POP_MULTILAYOUT_TYPE_NOTIFICATION;

	$jquery_constants['SETTINGS_PARAMSSCOPE_URL'] = GD_SETTINGS_PARAMSSCOPE_URL;

	$jquery_constants['TEMPLATE_PAGESECTIONGROUP_ID'] = GD_TEMPLATEID_PAGESECTIONGROUP_ID;

	// wpEditor
	$jquery_constants['TEMPLATE_EDITOR_NAME'] = GD_TEMPLATESETTINGS_EDITOR_NAME;
	
	$jquery_constants['SEPARATOR'] = GD_SEPARATOR;
	$jquery_constants['SPINNER'] = GD_CONSTANT_LOADING_SPINNER;

	// $jquery_constants['INTERCEPTOR_WITHPARAMS'] = GD_INTERCEPTOR_WITHPARAMS;
	$jquery_constants['JS_INITIALIZED'] = GD_JS_INITIALIZED;
	$jquery_constants['CRITICALJS_INITIALIZED'] = GD_CRITICALJS_INITIALIZED;

	$jquery_constants['CBACTION_LOADCONTENT'] = GD_TEMPLATECALLBACK_ACTION_LOADCONTENT;
	$jquery_constants['CBACTION_REFETCH'] = GD_TEMPLATECALLBACK_ACTION_REFETCH;
	$jquery_constants['CBACTION_RESET'] = GD_TEMPLATECALLBACK_ACTION_RESET;

	$jquery_constants['ERROR_MSG'] = sprintf(
		'%s <a href="{0}" target="{1}">%s</a>',
		GD_CONSTANT_ERROR_MSG,
		GD_CONSTANT_RETRY_MSG
	);
	$jquery_constants['ERROR_OFFLINE'] = sprintf(
		'%s <a href="{0}" target="{1}">%s</a>',
		GD_CONSTANT_OFFLINE_MSG,
		GD_CONSTANT_RETRY_MSG
	);
	$jquery_constants['ERROR_404'] = __('Ops, this is a broken link.', 'pop-coreprocessors');
	$jquery_constants['FEEDBACKMSG_MULTIDOMAIN'] = GD_CONSTANT_FEEDBACKMSG_MULTIDOMAIN;
	
	$jquery_constants['KEYS_THUMBPRINT'] = POP_KEYS_THUMBPRINT;

	
	// $jquery_constants['ERROR_404'] = sprintf(
	// 	'%s<br/>%s',
	// 	__('Ops, this is a broken link.', 'pop-coreprocessors'),
	// 	$msg_holder
	// );
	// Template Manager Status Error messages
	// $msg_holder = sprintf(
	// 	__('If you don\'t mind, please <a href="mailto:%s?subject=%s&body={0}">notify our admins</a> about this problem.', 'pop-coreprocessors'),
	// 	get_bloginfo('admin_email'),
	// 	str_replace(' ', '%20', sprintf(__('Problem found in %s', 'pop-coreprocessors'), get_bloginfo('name')))
	// );	
	// $jquery_constants['ERROR_MSG'] = sprintf(
	// 	'%s<br/>%s<br/>%s',
	// 	__('Ops, there was a problem!', 'pop-coreprocessors'),
	// 	__('Please try again, or reload this page.', 'pop-coreprocessors'),
	// 	$msg_holder
	// );
	// $jquery_constants['ERROR_404'] = sprintf(
	// 	'%s<br/>%s',
	// 	__('Ops, this is a broken link!', 'pop-coreprocessors'),
	// 	$msg_holder
	// );

	return $jquery_constants;
}

add_filter('gd_hack:script_loader:default_error', 'gd_wp_script_loader_default_error');
function gd_wp_script_loader_default_error($error) {

	return __('Ops, the upload failed. Let\'s fix this: please save your post as \'Draft\', refresh the browser window, and try again.', 'pop-coreprocessors');
}