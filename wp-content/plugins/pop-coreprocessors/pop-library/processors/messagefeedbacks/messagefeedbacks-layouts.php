<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWUSERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-inviteusers'));

class PoP_Core_Template_Processor_MessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWUSERS,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWUSERS:
			
				$ret['success-header'] = __('Invite successful!', 'pop-coreprocessors');
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Core_Template_Processor_MessageFeedbackLayouts();