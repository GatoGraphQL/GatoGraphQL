<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-events'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PASTEVENTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-pastevents'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYEVENTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-myevents'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPASTEVENTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-mypastevents'));

class GD_EM_Template_Processor_CustomListMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PASTEVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYEVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPASTEVENTS,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PASTEVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PASTEVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYEVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYEVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPASTEVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYPASTEVENTS,
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
new GD_EM_Template_Processor_CustomListMessageFeedbackFrameLayouts();