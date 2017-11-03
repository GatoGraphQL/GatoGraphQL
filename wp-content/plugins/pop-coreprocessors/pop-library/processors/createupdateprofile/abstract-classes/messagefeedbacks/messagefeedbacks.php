<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_CREATEPROFILE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-createprofile'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_UPDATEPROFILE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-updateprofile'));

class GD_Template_Processor_ProfileMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_CREATEPROFILE,
			GD_TEMPLATE_MESSAGEFEEDBACK_UPDATEPROFILE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_CREATEPROFILE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_CREATEPROFILE,
			GD_TEMPLATE_MESSAGEFEEDBACK_UPDATEPROFILE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPDATEPROFILE,
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
new GD_Template_Processor_ProfileMessageFeedbacks();