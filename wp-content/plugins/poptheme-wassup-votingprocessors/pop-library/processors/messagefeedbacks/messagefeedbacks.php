<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('messagefeedback-opinionatedvotes'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('messagefeedback-myopinionatedvotes'));

class VotingProcessors_Template_Processor_CustomListMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTES,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYOPINIONATEDVOTES,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTES,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYOPINIONATEDVOTES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYOPINIONATEDVOTES,
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
new VotingProcessors_Template_Processor_CustomListMessageFeedbacks();