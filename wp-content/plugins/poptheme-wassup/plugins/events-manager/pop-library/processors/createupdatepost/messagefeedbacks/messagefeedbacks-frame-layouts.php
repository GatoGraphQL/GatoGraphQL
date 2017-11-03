<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_CREATE', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-event-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_UPDATE', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-event-update'));

class GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_UPDATE
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_UPDATE,
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
new GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts();