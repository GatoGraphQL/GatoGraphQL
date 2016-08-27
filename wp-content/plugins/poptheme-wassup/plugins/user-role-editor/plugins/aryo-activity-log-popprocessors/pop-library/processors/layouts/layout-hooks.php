<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_AAL_CustomMultipleLayoutHooks {

	function __construct() {

		// add_filter(
		// 	'GD_Template_Processor_MultipleNotificationLayouts:layouts:details', 
		// 	array($this, 'multiple_details')
		// );
		// add_filter(
		// 	'GD_Template_Processor_MultipleNotificationLayouts:layouts:list', 
		// 	array($this, 'multiple_list')
		// );
		add_filter(
			'GD_Template_Processor_PreviewNotificationLayoutsBase:get_bottom_layouts', 
			array($this, 'get_bottom_layouts')
		);
		add_filter(
			'GD_Template_Processor_MultipleComponentLayouts:modules', 
			array($this, 'get_quicklinkgroup_bottom')
		);
	}

	function get_bottom_layouts($layouts) {

		// Add layout for action "updated_user_membership"
		array_unshift($layouts, GD_URE_AAL_TEMPLATE_MULTICOMPONENTWRAPPER_LAYOUTUSER_MEMBERSHIP);
		return $layouts;
	}

	function get_quicklinkgroup_bottom($layouts) {

		// Add layout for action "joined_community"
		$layouts[] = GD_URE_AAL_TEMPLATE_QUICKLINKGROUPWRAPPER_USER_JOINEDCOMMUNITY;
		return $layouts;
	}

	// function multiple_details($layouts) {

	// 	// Add the poststance at the end
	// 	$layouts['User-'.URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY] = GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_DETAILS;
	// 	$layouts['User-'.URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP] = GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_DETAILS;
	// 	return $layouts;
	// }
	// function multiple_list($layouts) {

	// 	// Add the poststance at the end
	// 	$layouts['User-'.URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY] = GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_LIST;
	// 	$layouts['User-'.URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP] = GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_LIST;
	// 	return $layouts;
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_AAL_CustomMultipleLayoutHooks();
