<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-opinionatedvote-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-opinionatedvote-update'));

class VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_UPDATE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_OPINIONATEDVOTE_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_UPDATE,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbackInners();