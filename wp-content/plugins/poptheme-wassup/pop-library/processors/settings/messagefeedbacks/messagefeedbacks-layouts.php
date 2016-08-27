<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SETTINGS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-settings'));

class GD_Template_Processor_SettingsMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SETTINGS
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SETTINGS:

				$ret['success-header'] = __('Alright, everything set up', 'poptheme-wassup');
				$ret['success'] = sprintf(
					'%s %s', 
					GD_CONSTANT_LOADING_SPINNER,
					__('Redirecting, please wait...', 'poptheme-wassup')
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SettingsMessageFeedbackLayouts();