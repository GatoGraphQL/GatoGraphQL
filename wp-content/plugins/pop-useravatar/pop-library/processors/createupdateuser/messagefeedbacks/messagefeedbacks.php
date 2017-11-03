<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_USERAVATAR_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-useravatar-update'));

class PoP_UserAvatar_Template_Processor_UserMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_USERAVATAR_UPDATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_USERAVATAR_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_USERAVATAR_UPDATE,
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
new PoP_UserAvatar_Template_Processor_UserMessageFeedbacks();