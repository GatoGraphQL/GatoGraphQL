<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CREATEPROFILE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-createprofile'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPDATEPROFILE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-updateprofile'));

class GD_Template_Processor_ProfileMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CREATEPROFILE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPDATEPROFILE,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CREATEPROFILE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CREATEPROFILE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPDATEPROFILE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPDATEPROFILE,
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
new GD_Template_Processor_ProfileMessageFeedbackFrameLayouts();