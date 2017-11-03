<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SETTINGS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-settings'));

class GD_Template_Processor_SettingsMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SETTINGS,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SETTINGS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SETTINGS,
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
new GD_Template_Processor_SettingsMessageFeedbackFrameLayouts();