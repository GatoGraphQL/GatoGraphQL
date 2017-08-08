<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INITIALIZEDOMAIN', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-initializedomain'));

class GD_Template_Processor_DomainMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INITIALIZEDOMAIN,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INITIALIZEDOMAIN => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INITIALIZEDOMAIN,
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
new GD_Template_Processor_DomainMessageFeedbackFrameLayouts();