<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWUSER', PoP_ServerUtils::get_template_definition('aal-quicklinkbuttongroup-viewuser'));
define ('GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD', PoP_ServerUtils::get_template_definition('aal-quicklinkbuttongroup-notification-markasreadunread'));

class GD_AAL_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWUSER,
			GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {

			case GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWUSER:

				$ret[] = GD_AAL_TEMPLATE_BUTTON_USERVIEW;
				break;

			case GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD:

				$ret[] = GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD;
				$ret[] = GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_QuicklinkButtonGroups();