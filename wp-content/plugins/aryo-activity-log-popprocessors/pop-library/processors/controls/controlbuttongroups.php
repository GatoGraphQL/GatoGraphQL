<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('AAL_POPPROCESSORS_TEMPLATE_CONTROLBUTTONGROUP_NOTIFICATIONLIST', PoP_TemplateIDUtils::get_template_definition('aal-popprocessors-controlbuttongroup-notificationlist'));
define ('AAL_POPPROCESSORS_TEMPLATE_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD', PoP_TemplateIDUtils::get_template_definition('aal-popprocessors-controlbuttongroup-notifications-markallasread'));

class AAL_PoPProcessors_Template_Processor_ControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			AAL_POPPROCESSORS_TEMPLATE_CONTROLBUTTONGROUP_NOTIFICATIONLIST,
			AAL_POPPROCESSORS_TEMPLATE_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case AAL_POPPROCESSORS_TEMPLATE_CONTROLBUTTONGROUP_NOTIFICATIONLIST:

				$ret[] = AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS;
				break;
		
			case AAL_POPPROCESSORS_TEMPLATE_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD:

				$ret[] = AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_Template_Processor_ControlButtonGroups();