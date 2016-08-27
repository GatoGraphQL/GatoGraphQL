<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-farm-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-farm-update'));

class OP_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_UPDATE,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FARM_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FARM_UPDATE,
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
new OP_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts();