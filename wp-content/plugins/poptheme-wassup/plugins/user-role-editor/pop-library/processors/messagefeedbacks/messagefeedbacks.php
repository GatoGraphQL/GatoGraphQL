<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_MEMBERS', PoP_ServerUtils::get_template_definition('messagefeedback-members'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('messagefeedback-organizations'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_INDIVIDUALS', PoP_ServerUtils::get_template_definition('messagefeedback-individuals'));

class GD_URE_Template_Processor_CustomListMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_MEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_ORGANIZATIONS,
			GD_TEMPLATE_MESSAGEFEEDBACK_INDIVIDUALS,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_MEMBERS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_ORGANIZATIONS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_ORGANIZATIONS,
			GD_TEMPLATE_MESSAGEFEEDBACK_INDIVIDUALS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_INDIVIDUALS,
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
new GD_URE_Template_Processor_CustomListMessageFeedbacks();