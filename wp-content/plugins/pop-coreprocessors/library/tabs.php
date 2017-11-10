<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_IDS_TABS_REMEMBERCHECKBOX', 'tabs-remember');

add_filter('gd_jquery_constants', 'popcore_tabs_jquery_constants');
function popcore_tabs_jquery_constants($jquery_constants) {

	// Do not open the tabs if setting the config by the querystring on the URL
	$vars = GD_TemplateManager_Utils::get_vars();
	$opentabs = !$vars['config'];
	$opentabs = apply_filters(
		'popcore_tabs_jquery_constants:opentabs',
		$opentabs
	);
	$jquery_constants['OPENTABS'] = $opentabs ? true : "";

	$jquery_constants['IDS_TABS_REMEMBERCHECKBOX'] = POP_IDS_TABS_REMEMBERCHECKBOX;

	// Re-open tabs? Add 'data-dismiss="alert"' so that it always closes the alert, either pressing accept or cancel
	$msg_placeholder = '<div class="pop-notificationmsg %s alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" aria-hidden="true" data-dismiss="alert">×</button>%s</div>';
	$btn_placeholder = '<button type="button" class="btn btn-default" aria-hidden="true" data-dismiss="alert" %s>%s</button>';
	$btns = 
		'<div class="btn-group btn-group-xs">'.
			sprintf(
				$btn_placeholder,
				'onclick="{0}"',
				__('Accept', 'pop-coreprocessors')
			).
			sprintf(
				$btn_placeholder,
				'onclick="{1}"',
				__('Cancel', 'pop-coreprocessors')
			).
		'</div>';
	$checkbox = sprintf(
		'<div class="checkbox">'.
			'<label>'.
				'<input type="checkbox" id="%s">%s'.
			'</label>'.
		'</div>',
		POP_IDS_TABS_REMEMBERCHECKBOX,
		__('Remember', 'pop-coreprocessors')
	);

	$formgroup_placeholder = '%s';//'<div class="form-group">%s</div>';
	$message = sprintf(
		$msg_placeholder,
		'website-level sessiontabs',
		sprintf(
			'%s%s%s',//'<div class="form-inline">%s%s%s</div>',
			sprintf(
				$formgroup_placeholder,
				__('Reopen previous session tabs?', 'pop-coreprocessors')
			),
			sprintf(
				$formgroup_placeholder,
				$btns
			),
			sprintf(
				$formgroup_placeholder,
				$checkbox
			)
		)
	);
	$jquery_constants['TABS_REOPENMSG'] = apply_filters('pop_sw_message:reopentabs', $message);

	return $jquery_constants;
}
