<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('AAL_POPPROCESSORS_TEMPLATE_CONTROLGROUP_NOTIFICATIONLIST', PoP_TemplateIDUtils::get_template_definition('aal-popprocessors-controlgroup-notificationlist'));

class AAL_PoPProcessors_Template_Processor_ControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			AAL_POPPROCESSORS_TEMPLATE_CONTROLGROUP_NOTIFICATIONLIST,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		switch ($template_id) {
				
			case AAL_POPPROCESSORS_TEMPLATE_CONTROLGROUP_NOTIFICATIONLIST:

				$ret[] = AAL_POPPROCESSORS_TEMPLATE_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_LOADLATESTBLOCK;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_Template_Processor_ControlGroups();