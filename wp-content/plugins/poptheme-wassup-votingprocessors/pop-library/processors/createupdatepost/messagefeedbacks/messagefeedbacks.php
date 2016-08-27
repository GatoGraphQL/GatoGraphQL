<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('messagefeedback-opinionatedvote-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedback-opinionatedvote-update'));

class VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_UPDATE,
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
new VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbacks();