<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUS', PoP_ServerUtils::get_template_definition('messagefeedback-contactus'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUSER', PoP_ServerUtils::get_template_definition('messagefeedback-contactuser'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_SHAREBYEMAIL', PoP_ServerUtils::get_template_definition('messagefeedback-sharebyemail'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_VOLUNTEER', PoP_ServerUtils::get_template_definition('messagefeedback-volunteer'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_FLAG', PoP_ServerUtils::get_template_definition('messagefeedback-flag'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_NEWSLETTER', PoP_ServerUtils::get_template_definition('messagefeedback-newsletter'));

class GD_GF_Template_Processor_MessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUS,
			GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUSER,
			GD_TEMPLATE_MESSAGEFEEDBACK_SHAREBYEMAIL,
			GD_TEMPLATE_MESSAGEFEEDBACK_VOLUNTEER,
			GD_TEMPLATE_MESSAGEFEEDBACK_FLAG,
			GD_TEMPLATE_MESSAGEFEEDBACK_NEWSLETTER,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUS,
			GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUSER => GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUSER,
			GD_TEMPLATE_MESSAGEFEEDBACK_SHAREBYEMAIL => GD_TEMPLATE_MESSAGEFEEDBACKINNER_SHAREBYEMAIL,
			GD_TEMPLATE_MESSAGEFEEDBACK_VOLUNTEER => GD_TEMPLATE_MESSAGEFEEDBACKINNER_VOLUNTEER,
			GD_TEMPLATE_MESSAGEFEEDBACK_FLAG => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FLAG,
			GD_TEMPLATE_MESSAGEFEEDBACK_NEWSLETTER => GD_TEMPLATE_MESSAGEFEEDBACKINNER_NEWSLETTER,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_MessageFeedbacks();