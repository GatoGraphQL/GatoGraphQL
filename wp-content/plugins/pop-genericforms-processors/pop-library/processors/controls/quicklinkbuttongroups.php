<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postvolunteer'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-usersendmessage'));

class PoPCore_GenericForms_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_QuicklinkButtonGroups();