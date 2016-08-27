<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-opinionatedvote-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-opinionatedvote-update'));

class VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_UPDATE,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTE_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE,
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
new VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts();