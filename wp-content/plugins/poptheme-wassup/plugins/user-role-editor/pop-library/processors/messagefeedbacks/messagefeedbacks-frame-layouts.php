<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MEMBERS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-members'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-organizations'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INDIVIDUALS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-individuals'));

class GD_URE_Template_Processor_CustomListMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MEMBERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ORGANIZATIONS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INDIVIDUALS,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MEMBERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MEMBERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ORGANIZATIONS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ORGANIZATIONS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INDIVIDUALS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INDIVIDUALS,
		);

		if ($layout = $layouts[$template_id]) {

			return $layout;
		}

		return parent::get_layout($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomListMessageFeedbackFrameLayouts();