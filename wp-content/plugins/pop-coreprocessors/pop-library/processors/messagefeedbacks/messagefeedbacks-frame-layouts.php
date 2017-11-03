<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INVITENEWUSERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-inviteusers'));

class PoP_Core_Template_Processor_MessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INVITENEWUSERS,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INVITENEWUSERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWUSERS,
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
new PoP_Core_Template_Processor_MessageFeedbackFrameLayouts();