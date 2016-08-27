<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CREATELOCATION', PoP_ServerUtils::get_template_definition('layout-messagefeedback-createlocation'));

class GD_Template_Processor_CreateLocationMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CREATELOCATION
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CREATELOCATION:

				$ret['empty-town'] = __('City is missing.', 'em-popprocessors');
				$ret['success-header'] = __('Location added successfully!', 'em-popprocessors');
				$ret['success'] = __('More locations to add?', 'em-popprocessors');
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateLocationMessageFeedbackLayouts();