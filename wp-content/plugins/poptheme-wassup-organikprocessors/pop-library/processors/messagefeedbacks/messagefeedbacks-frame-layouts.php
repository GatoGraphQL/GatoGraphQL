<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARMS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-farms'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYFARMS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-myfarms'));

class OP_Template_Processor_CustomListMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARMS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYFARMS,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARMS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FARMS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYFARMS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYFARMS,
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
new OP_Template_Processor_CustomListMessageFeedbackFrameLayouts();