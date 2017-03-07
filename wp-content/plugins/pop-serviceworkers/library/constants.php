<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_SW_URLPARAM_NETWORKFIRST', 'sw-networkfirst');
// define ('POP_SW_IDS_CHECKBOX_REMEMBER', 'sw-remember');

/** 
 * Important: this same value must be set in the .htaccess to make an internal redirect, so the cache bust parameter
 * is ignored when doing the request, so allowing WP Super Cache to return a cached version
 * .htaccess code:
 * 
 * 	# case: leading and trailing parameters
 * 	RewriteCond %{QUERY_STRING} ^(.+)&sw-cachebust=[0-9a-z]+&(.+)$ [NC]
 * 	RewriteRule (.*) /$1?%1&%2 [L]
 * 	# case: leading-only, trailing-only or no additional parameters
 * 	RewriteCond %{QUERY_STRING} ^(.+)&sw-cachebust=[0-9a-z]+$|^cachebust=[0-9a-z]+&?(.*)$ [NC]
 * 	RewriteRule (.*) /$1?%1 [L]
 * 
 */
define ('POP_SW_URLPARAM_CACHEBUST', 'sw-cachebust');

add_filter('GD_TemplateManager_Utils:current_url:remove_params', 'pop_sw_remove_urlparams');
function pop_sw_remove_urlparams($remove_params) {

	$remove_params[] = POP_SW_URLPARAM_NETWORKFIRST;
	$remove_params[] = POP_SW_URLPARAM_CACHEBUST;

	return $remove_params;
}

add_filter('gd_jquery_constants', 'pop_sw_jquery_constants');
function pop_sw_jquery_constants($jquery_constants) {

	$jquery_constants['SW_URLPARAM_NETWORKFIRST'] = POP_SW_URLPARAM_NETWORKFIRST;
	// $jquery_constants['SW_IDS_CHECKBOX_REMEMBER'] = POP_SW_IDS_CHECKBOX_REMEMBER;


	// Allow the PoPTheme Wassup indicate in which pagesections will show the "Please refresh this page" message
	$jquery_constants['SW_MAIN_PAGESECTIONS'] = apply_filters('pop_sw_main_pagesection_ids', array());

	// We don't want to fetch it from the network, but from the cache, so remove the filter that we've added

	remove_filter('get_reloadurl_linkattrs', 'pop_sw_reloadurl_linkattrs');	
	$reloadurl_linkattrs = get_reloadurl_linkattrs();
	add_filter('get_reloadurl_linkattrs', 'pop_sw_reloadurl_linkattrs');

	// The message html to be appended to the pageSection
	$msg_placeholder = '<div class="pop-notificationmsg %s alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" aria-hidden="true" data-dismiss="alert">×</button>%s</div>';
	$message = sprintf(
		$msg_placeholder,
		'page-level',
		sprintf(
			__('This page has been updated, <a href="%s" target="%s" %s>click here to refresh it</a>.', 'pop-serviceworkers'),
			'{0}',
			'{1}',
			$reloadurl_linkattrs
		)
	);
	$jquery_constants['SW_MESSAGES_PAGEUPDATED'] = apply_filters('pop_sw_message:pageupdated', $message);

	// The "there is a new SW" message html to be appended to the status
	// 'topmost': show on top of any other message
	$message = sprintf(
		$msg_placeholder,
		'website-level topmost',
		sprintf(
			__('There is a new version of the website, <a href="%s" target="%s">click here to reload it</a>.', 'pop-serviceworkers'),
			'{0}',
			GD_URLPARAM_TARGET_FULL
		)
	);
	$jquery_constants['SW_MESSAGES_WEBSITEUPDATED'] = apply_filters('pop_sw_message:websiteupdated', $message);

	// // Re-open tabs? Add 'data-dismiss="alert"' so that it always closes the alert, either pressing accept or cancel
	// $btn_placeholder = '<button type="button" class="btn btn-default" aria-hidden="true" data-dismiss="alert" %s>%s</button>';
	// $btns = 
	// 	'<div class="btn-group btn-group-sm">'.
	// 		sprintf(
	// 			$btn_placeholder,
	// 			'onclick="{0}"',
	// 			__('Accept', 'pop-serviceworkers')
	// 		).
	// 		sprintf(
	// 			$btn_placeholder,
	// 			'onclick="{1}"',
	// 			__('Cancel', 'pop-serviceworkers')
	// 		).
	// 	'</div>';
	// $checkbox = sprintf(
	// 	'<div class="checkbox">'.
	// 		'<label>'.
	// 			'<input type="checkbox" id="%s">%s'.
	// 		'</label>'.
	// 	'</div>',
	// 	POP_SW_IDS_CHECKBOX_REMEMBER,
	// 	__('Remember', 'pop-serviceworkers')
	// );

	// $formgroup_placeholder = '%s';//'<div class="form-group">%s</div>';
	// $message = sprintf(
	// 	$msg_placeholder,
	// 	'website-level sessiontabs',
	// 	sprintf(
	// 		'%s%s%s',//'<div class="form-inline">%s%s%s</div>',
	// 		sprintf(
	// 			$formgroup_placeholder,
	// 			__('Reopen previous session tabs?', 'pop-serviceworkers')
	// 		),
	// 		sprintf(
	// 			$formgroup_placeholder,
	// 			$btns
	// 		),
	// 		sprintf(
	// 			$formgroup_placeholder,
	// 			$checkbox
	// 		)
	// 	)
	// );
	// $jquery_constants['SW_MESSAGES_REOPENTABS'] = apply_filters('pop_sw_message:reopentabs', $message);

	return $jquery_constants;
}


add_filter('get_reloadurl_linkattrs', 'pop_sw_reloadurl_linkattrs');
add_filter('GD_DataLoad_ActionExecuter_CreateUpdate_User:success_msg:linkattrs', 'pop_sw_reloadurl_linkattrs');
function pop_sw_reloadurl_linkattrs($params) {

	$params .= ' data-sw-networkfirst="true"';
	return $params;
}